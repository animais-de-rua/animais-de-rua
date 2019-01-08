<?php

namespace App\Http\Controllers;

use App\Helpers\EnumHelper;
use App\Mail\ApplyForm;
use App\Mail\ContactForm;
use App\Mail\GodfatherForm;
use App\Mail\TrainingForm;
use App\Mail\VolunteerForm;
use App\Models\Headquarter;
use App\Models\Process;
use Config;
use Image;
use Mail;
use Storage;

class FormController extends Controller
{
    public function form_view($slug)
    {
        $lang_map = [
            'voluntariado' => 'volunteer',
            'contacto' => 'contact',
            'candidatura' => 'apply',
            'formacao' => 'training',
        ];

        if (array_key_exists($slug, $lang_map)) {
            $slug = $lang_map[$slug];
        }

        \Session::put('form', $slug);

        return redirect('/');
    }

    public function form_submit($slug)
    {
        if (method_exists($this, "form_submit_$slug")) {
            return call_user_func(array($this, "form_submit_$slug"));
        }
    }

    public function form_submit_volunteer()
    {
        $validatedData = request()->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|min:9|max:16',
            'age' => 'required|numeric',
            'job' => 'required',
            'district' => 'required',
            'county' => 'required',
            'schedule' => 'required',
            'interest' => 'required',
            'observations' => 'required',
        ]);

        // Mail to AdR
        $result = Mail::to(Config::get('settings.form_volunteer'))->send(new VolunteerForm(request()));

        return response()->json([
            'errors' => '',
            'message' => __('Your message has been successfully sent.') . '<br />' . __('We will contact you as soon as possible to follow up on your request.'),
        ]);
    }

    public function form_submit_contact()
    {
        $validatedData = request()->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|min:9|max:16',
            'district' => 'required',
            'county' => 'required',
            'subject' => 'required',
            'observations' => 'required',
        ]);

        // Mail to AdR
        $result = Mail::to(Config::get('settings.form_contact'))->send(new ContactForm(request()));

        return response()->json([
            'errors' => '',
            'message' => __('Your message has been successfully sent.') . '<br />' . __('We will contact you as soon as possible to follow up on your request.'),
        ]);
    }

    public function form_submit_apply()
    {
        $request = request();

        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|min:9|max:16',
            'process' => 'required',
            'animals' => 'required|numeric',
            'specie' => 'required|in:' . EnumHelper::keys('process.specie', ',') . ',other',
            'parish' => 'required|exists:territories,id',
            'images.*' => 'required|mimes:jpeg,jpg,png|max:5000',
            'observations' => 'required',
        ]);

        // Get Headquarter
        $headquarter = Headquarter::whereHas('territories', function ($query) use ($request) {
            $query->where('territory_id', $request->parish)
                ->orWhere('territory_id', $request->county)
                ->orWhere('territory_id', $request->district);
        })->first();

        // Save Images
        $images_value = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file->isValid()) {
                    $image = Image::make($file);

                    // Filename
                    $filename = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file->getClientOriginalName()) . '_' . time() . '.jpg';

                    // Save Image
                    Storage::disk('uploads')->put("process/$filename", $image
                            ->resize(800, 600, function ($c) {$c->aspectRatio();})
                            ->stream('jpg', 82));

                    // Save Thumb
                    Storage::disk('uploads')->put("process/thumb/$filename", $image
                            ->resize(340, 255, function ($c) {$c->aspectRatio();})
                            ->stream('jpg', 82));

                    $images_value[] = "uploads/process/$filename";
                }
            }
        }

        // Notes
        $notes = 'O candidato oferece-se para:<br />';
        foreach ($request->colab as $colab) {
            $notes .= '- ' . __("web.forms.$colab") . '<br />';
        }

        // Process
        $process = new Process();
        $process->fill($request->all());

        $process->name = $request->process;
        $process->contact = $request->name;
        $process->territory_id = $request->parish;
        $process->amount_other = $request->animals;
        $process->history = $request->observations;
        $process->images = $images_value;
        $process->notes = $notes;

        $process->status = 'approving';
        $process->urgent = 0;
        $process->headquarter_id = $headquarter->id;
        $process->save();

        // Mail to client
        $result = Mail::to($request->email)->send(new ApplyForm($process));

        return response()->json([
            'errors' => '',
            'message' => __('Your message has been successfully sent.') . '<br />' . __('We will contact you as soon as possible to follow up on your request.'),
        ]);
    }

    public function form_submit_training()
    {
        $validatedData = request()->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|min:9|max:16',
            'district' => 'required',
            'county' => 'required',
            'theme' => 'required',
            'observations' => 'required',
        ]);

        // Mail to AdR
        $result = Mail::to(Config::get('settings.form_training'))->send(new TrainingForm(request()));

        return response()->json([
            'errors' => '',
            'message' => __('Your message has been successfully sent.') . '<br />' . __('We will contact you as soon as possible to follow up on your request.'),
        ]);
    }

    public function form_submit_godfather()
    {
        $validatedData = request()->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|min:9|max:16',
            'other' => 'nullable|numeric',
            'observations' => 'required',
        ]);

        // Mail to AdR
        $result = Mail::to(Config::get('settings.form_godfather'))->send(new GodfatherForm(request()));

        return response()->json([
            'errors' => '',
            'message' => __('Your message has been successfully sent.') . '<br />' . __('We will contact you as soon as possible to follow up on your request.'),
        ]);
    }
}
