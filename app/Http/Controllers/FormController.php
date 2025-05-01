<?php

namespace App\Http\Controllers;

use App\Enums\FormsEnum;
use App\Enums\Process\StatusEnum as ProcessStatusEnum;
use App\Http\Requests\Form\FormSubmitApplyRequest;
use App\Http\Requests\Form\FormSubmitContactRequest;
use App\Http\Requests\Form\FormSubmitGodfatherRequest;
use App\Http\Requests\Form\FormSubmitPetsittingRequest;
use App\Http\Requests\Form\FormSubmitTrainingRequest;
use App\Http\Requests\Form\FormSubmitVolunteerRequest;
use App\Mail\ApplyForm;
use App\Mail\ContactForm;
use App\Mail\GodfatherForm;
use App\Mail\PetsittingForm;
use App\Mail\TrainingForm;
use App\Mail\VolunteerForm;
use App\Models\Headquarter;
use App\Models\Process;
use App\Models\StorePetsittingRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image;
use Spatie\Newsletter\Facades\Newsletter;

class FormController extends Controller
{
    public function formView(string $slug): RedirectResponse
    {
        Session::put('form', match ($slug) {
            'voluntariado' => 'volunteer',
            'contacto' => 'contact',
            'candidatura' => 'apply',
            'formacao' => 'training',
            'petsitting' => 'petsitting',
            default => null,
        });

        return redirect('/');
    }

    public function formSubmit(Request $request, string $slug): JsonResponse
    {
        $this->subscribeNewsletter($request);

        return match (FormsEnum::from($slug)) {
            FormsEnum::VOLUNTEER => $this->formSubmitVolunteer($request),
            FormsEnum::CONTACT => $this->formSubmitContact($request),
            FormsEnum::APPLY => $this->formSubmitApply($request),
            FormsEnum::TRAINING => $this->formSubmitTraining($request),
            FormsEnum::GODFATHER => $this->formSubmitGodfather($request),
            FormsEnum::PETSITTING => $this->formSubmitPetsitting($request),
        };
    }

    public function formSubmitVolunteer(FormSubmitVolunteerRequest $request): JsonResponse
    {
        // Mail to AdR
        Mail::to(Config::get('settings.form_volunteer'))->send(new VolunteerForm($request));

        return response()->json([
            'success' => true,
            'message' => __('Your message has been successfully sent.').'<br />'.__('We will contact you as soon as possible to follow up on your request.'),
        ]);
    }

    public function formSubmitContact(FormSubmitContactRequest $request): JsonResponse
    {
        // Mail to AdR
        Mail::to(Config::get('settings.form_contact'))->send(new ContactForm($request));

        return response()->json([
            'success' => true,
            'message' => __('Your message has been successfully sent.').'<br />'.__('We will contact you as soon as possible to follow up on your request.'),
        ]);
    }

    public function formSubmitApply(FormSubmitApplyRequest $request): JsonResponse
    {
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
                    $filename = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file->getClientOriginalName()).'_'.time().'.jpg';

                    // Save Image
                    Storage::disk('uploads')->put("process/$filename", $image
                        ->resize(800, 600, function ($c) {
                            $c->aspectRatio();
                        })
                        ->stream('jpg', 82));

                    // Save Thumb
                    Storage::disk('uploads')->put("process/thumb/$filename", $image
                        ->resize(340, 255, function ($c) {
                            $c->aspectRatio();
                        })
                        ->stream('jpg', 82));

                    $images_value[] = "uploads/process/$filename";
                }
            }
        }

        // Notes
        $notes = 'O candidato oferece-se para:<br />';
        if (is_array($request->colab)) {
            foreach ($request->colab as $colab) {
                $notes .= '- '.__("web.forms.$colab").'<br />';
            }
        }

        // Process
        $process = new Process;
        $process->fill($request->all());

        $process->name = $request->process;
        $process->contact = $request->name;
        $process->territory_id = $request->parish;
        $process->amount_other = $request->animals;
        $process->history = $request->observations;
        $process->images = $images_value;
        $process->notes = $notes;
        $process->address = $request->address.', '.$request->postalcode;

        $process->status = ProcessStatusEnum::APPROVING;
        $process->urgent = 0;
        $process->headquarter_id = $headquarter->id;
        $process->save();

        // Mail to client
        Mail::to($request->email)->send(new ApplyForm($process));

        return response()->json([
            'success' => true,
            'message' => __('Your message has been successfully sent.').'<br />'.__('We will contact you as soon as possible to follow up on your request.'),
        ]);
    }

    public function formSubmitTraining(FormSubmitTrainingRequest $request): JsonResponse
    {
        // Mail to AdR
        Mail::to(Config::get('settings.form_training'))->send(new TrainingForm($request));

        return response()->json([
            'success' => true,
            'message' => __('Your message has been successfully sent.').'<br />'.__('We will contact you as soon as possible to follow up on your request.'),
        ]);
    }

    public function formSubmitGodfather(FormSubmitGodfatherRequest $request): JsonResponse
    {
        // Mail to AdR
        Mail::to(config('settings.form_godfather'))
            ->send(new GodfatherForm($request));

        return response()->json([
            'success' => true,
            'message' => __('Your message has been successfully sent.').'<br />'.__('We will contact you as soon as possible to follow up on your request.'),
        ]);
    }

    public function formSubmitPetsitting(FormSubmitPetsittingRequest $request): JsonResponse
    {
        // Send Mail with last stored request id
        $lastEmailId = StorePetsittingRequests::all()->last()->id ?? 0;
        Mail::to(Config::get('settings.form_petsitting'))->send(new PetsittingForm($request, $lastEmailId + 1));

        // Store form request
        $storePetsittingRequests = new StorePetsittingRequests;
        $storePetsittingRequests->name = $request->first_name.' '.$request->last_name;
        $storePetsittingRequests->save();

        return response()->json([
            'success' => true,
            'message' => __('Your form has been successfully submitted.'),
        ]);
    }

    private function subscribeNewsletter(Request $request): void
    {
        if ($request->input('newsletter')) {
            Newsletter::subscribe($request->input('email'));
        }
    }
}
