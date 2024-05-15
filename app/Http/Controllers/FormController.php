<?php

namespace App\Http\Controllers;

use App\Helpers\EnumHelper;
use App\Mail\ApplyForm;
use App\Mail\ContactForm;
use App\Mail\GodfatherForm;
use App\Mail\PetsittingForm;
use App\Mail\TrainingForm;
use App\Mail\VolunteerForm;
use App\Models\Headquarter;
use App\Models\Process;
use App\Models\StorePetsittingRequests;
use Config;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Image;
use Mail;
use Newsletter;
use Storage;
use Validator;

class FormController extends Controller
{
    public function form_view($slug)
    {
        $lang_map = [
            'voluntariado' => 'volunteer',
            'contacto' => 'contact',
            'candidatura' => 'apply',
            'formacao' => 'training',
            'petsitting' => 'petsitting',
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

        $this->subscribe_newsletter();

        // Mail to AdR
        $result = Mail::to(Config::get('settings.form_volunteer'))->send(new VolunteerForm(request()));

        return response()->json([
            'success' => true,
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

        $this->subscribe_newsletter();

        // Mail to AdR
        $result = Mail::to(Config::get('settings.form_contact'))->send(new ContactForm(request()));

        return response()->json([
            'success' => true,
            'message' => __('Your message has been successfully sent.') . '<br />' . __('We will contact you as soon as possible to follow up on your request.'),
        ]);
    }

    public function form_submit_apply()
    {
        $request = request();

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|min:9|max:16',
            'process' => 'required',
            'address' => 'required',
            'postalcode' => 'required',
            'animals' => 'required|numeric',
            'specie' => 'required|in:' . EnumHelper::keys('process.specie', ',') . ',other',
            'parish' => 'required|exists:territories,id',
            'images.*' => 'required|mimes:jpeg,jpg,png|max:5000',
            'observations' => 'required|min:3',
        ]);

        $validator->validate();

        // Check for 3 minimum images
        if (count($request->file('images')) < 3) {
            $validator->errors()->add('images', __('You must upload at least 3 images.'));
            throw new ValidationException($validator);
        }

        // Check for colab
        if (!$request->input('colab')) {
            $validator->errors()->add('colab', __('You must select at least one option on how you may collaborate.'));
            throw new ValidationException($validator);
        }

        $this->subscribe_newsletter();

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
        if (is_array($request->colab)) {
            foreach ($request->colab as $colab) {
                $notes .= '- ' . __("web.forms.$colab") . '<br />';
            }
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
        $process->address = $request->address . ', ' . $request->postalcode;

        $process->status = 'approving';
        $process->urgent = 0;
        $process->headquarter_id = $headquarter->id;
        $process->save();

        // Mail to client
        $result = Mail::to($request->email)->send(new ApplyForm($process));

        return response()->json([
            'success' => true,
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

        $this->subscribe_newsletter();

        // Mail to AdR
        $result = Mail::to(Config::get('settings.form_training'))->send(new TrainingForm(request()));

        return response()->json([
            'success' => true,
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

        $this->subscribe_newsletter();

        // Mail to AdR
        $result = Mail::to(Config::get('settings.form_godfather'))->send(new GodfatherForm(request()));

        return response()->json([
            'success' => true,
            'message' => __('Your message has been successfully sent.') . '<br />' . __('We will contact you as soon as possible to follow up on your request.'),
        ]);
    }

    public function form_submit_petsitting()
    {
        $request = request();

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:35',
            'last_name' => 'required|max:35',
            'email' => 'required|email',
            'phone' => 'required|min:9|max:16',
            'address' => 'required|max:255',
            'city' => 'required|max:35',
            'town' => 'required|max:35',
            'initial_date' => 'required|date',
            'final_date' => 'required|date|after_or_equal:initialDate',
            'animals' => 'required|array|min:1',
            'other_animals' => Rule::requiredIf(function () use ($request) {
                if ($request->filled('animals')) {
                    return in_array('Outros', $request->input('animals'));
                }
            }),
            'number_of_animals' => 'required|numeric|min:1|max:2',
            'animal_temper' => 'required|max:255',
            'visit_number' => 'required|numeric',
            'walk_number' => 'required_if:has_walk,yes|numeric',
            'services' => 'nullable',
            'notes' => 'nullable|max:255',
            'has_consent' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __('Some fields are required. Please check the form and try again.'),
                'errors' => $validator->errors(),
            ]);
        }

        // Send Mail with last stored request id
        $lastEmailId = StorePetsittingRequests::all()->last()->id ?? 0;
        Mail::to(Config::get('settings.form_petsitting'))->send(new PetsittingForm($request, $lastEmailId + 1));

        // Store form request
        $storePetsittingRequests = new StorePetsittingRequests();
        $storePetsittingRequests->name = $request->first_name . ' ' . $request->last_name;
        $storePetsittingRequests->save();

        return response()->json([
            'success' => true,
            'message' => __('Your form has been successfully submitted.'),
        ]);
    }

    private function subscribe_newsletter()
    {
        if (request()->input('newsletter')) {
            Newsletter::subscribe(request()->input('email'));
        }
    }
}
