<?php

namespace App\Http\Controllers;

use App\Helpers\EnumHelper;
use App\Helpers\LocalCache;
use App\Models\Adoption;
use App\Models\Process;
use GemaDigital\Http\Controllers\Traits\PageTrait;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Spatie\Newsletter\Facades\Newsletter;

class PageController
{
    use PageTrait;

    protected mixed $data;

    public function index($slug = 'home'): Factory|Application|View
    {
        $locale = Session::get('locale');

        $this->data = LocalCache::page($slug, $locale);

        // Common data to all pages
        $this->data = array_merge($this->data, $this->common());

        // Page specific data
        if (method_exists($this, $slug)) {
            $this->data = array_merge($this->data, call_user_func([$this, $slug]));
        }

        return view('pages.'.$this->data['page']->template, $this->data);
    }

    public function blank(): Factory|Application|View
    {
        return view('pages.blank', $this->common());
    }

    private function common(): array
    {
        $data = [];

        if (! Request::ajax()) {
            $treated = LocalCache::treated();
            $adopted = LocalCache::adopted();
            $form_acting_territories = LocalCache::headquarters_territories_acting();
            $form_all_territories = LocalCache::territories_form_all();

            $base_counter = Config::get('settings.base_counter', 0);

            $data = [
                'total_interventions' => $base_counter + $treated + $adopted,
                'form_acting_territories' => $form_acting_territories,
                'form_all_territories' => $form_all_territories,
            ];
        }

        return $data;
    }

    private function home(): array
    {
        $campaigns = LocalCache::campaigns();
        $products = LocalCache::products();
        $processes_urgent = LocalCache::processes_urgent();

        return [
            'processes' => $processes_urgent,
            'campaigns' => $campaigns,
            'products' => $products,
        ];
    }

    private function association(): array
    {
        $headquarters = LocalCache::headquarters();

        return [
            'headquarters' => $headquarters,
        ];
    }

    private function ced(): array
    {
        return [];
    }

    private function animals(): array
    {
        $districts_godfather = LocalCache::processes_districts_godfather();
        $districts_adoption = LocalCache::adoptions_districts_adoption();
        $processes_urgent = LocalCache::processes_urgent();

        $species = EnumHelper::translate('process.specie');

        return [
            'processes' => $processes_urgent,
            'species' => $species,
            'districts' => [
                'godfather' => $districts_godfather,
                'adoption' => $districts_adoption,
            ],
        ];
    }

    public function animalsView($option, $id): Factory|Application|View
    {
        switch ($option) {
            case 'godfather':
                $animal = Process::select(['processes.name', 'history', 'specie', 'images', 'created_at', 'district.name as district', 'district.id as district_id', 'county.name as county'])
                    ->join('territories as district', 'district.id', '=', DB::raw('LEFT(territory_id, 2)'))
                    ->join('territories as county', 'county.id', '=', DB::raw('LEFT(territory_id, 4)'))
                    ->where('processes.id', $id)
                    ->firstOrFail()->toArray();

                $other = null;
                break;
            default:
            case 'adoption':
                $animal = Adoption::select(['adoptions.name', 'adoptions.history', 'processes.specie', 'adoptions.images', 'adoptions.created_at', 'district.id as district_id', 'district.name as district', 'county.name as county'])
                    ->join('processes', 'processes.id', '=', 'adoptions.process_id')
                    ->join('fats', 'fats.id', '=', 'adoptions.fat_id')
                    ->join('territories as district', 'district.id', '=', DB::raw('LEFT(fats.territory_id, 2)'))
                    ->join('territories as county', 'county.id', '=', DB::raw('LEFT(fats.territory_id, 4)'))
                    ->where('adoptions.id', $id)
                    ->firstOrFail()->toArray();

                $district = $animal['district_id'];

                $other = Adoption::select(['adoptions.id', 'adoptions.name', 'adoptions.history', 'processes.specie', 'adoptions.images', 'adoptions.created_at'])
                    ->join('processes', 'processes.id', '=', 'adoptions.process_id')
                    ->join('fats', 'fats.id', '=', 'adoptions.fat_id')
                    ->where('adoptions.id', '<>', $id)
                    ->where(DB::raw('LEFT(fats.territory_id, 2)'), $district)
                    ->where('adoptions.status', 'open')
                    ->orderBy('created_at', 'desc')
                    ->limit(3)
                    ->get()->toArray();
                break;
        }

        // Common data to all pages
        $data = array_merge(['animal' => $animal, 'other' => $other, 'option' => $option], $this->common());

        return view('pages.animals-view', $data);
    }

    private function help(): array
    {
        return [];
    }

    private function partners(): array
    {
        $sponsors = LocalCache::sponsors();

        return [
            'sponsors' => $sponsors,
        ];
    }

    private function petsitting(): array
    {
        $petsitters = LocalCache::petsitters();

        return [
            'petsitters' => $petsitters,
        ];
    }

    private function friends(): array
    {
        $modalities = LocalCache::friend_card_modalities();
        $partners = LocalCache::partners();
        $partners_categories = LocalCache::partners_categories();
        $partners_territories = LocalCache::partners_territories();

        return [
            'subscribed' => isset($_GET['success']),
            'hasAccess' => backpack_user() && (is(['admin', 'volunteer']) || backpack_user()->friend_card_modality()->first()),
            'modalities' => $modalities,
            'partners' => [
                'list' => $partners,
                'categories' => $partners_categories,
                'territories' => $partners_territories,
            ],
        ];
    }

    // API
    public function getAnimalsAdoption($territory = 0, $specie = 0): JsonResponse
    {
        $data = Adoption::query()->select(['adoptions.id', 'adoptions.name', 'processes.specie', 'adoptions.images', 'adoptions.created_at', 'district.name as district', 'county.name as county'])
            ->join('processes', 'processes.id', '=', 'adoptions.process_id')
            ->join('fats', 'fats.id', '=', 'adoptions.fat_id')
            ->join('territories as district', 'district.id', '=', DB::raw('LEFT(fats.territory_id, 2)'))
            ->join('territories as county', 'county.id', '=', DB::raw('LEFT(fats.territory_id, 4)'))
            ->where('adoptions.status', 'open')
            ->orderBy('created_at', 'desc')
            ->limit(20);

        // Specie
        if ($specie) {
            $data = $data->where('processes.specie', $specie);
        }

        // Territory
        if ($territory > 0) {
            $territory = str_pad($territory, 2, 0, STR_PAD_LEFT);
            $data = $data->where('fats.territory_id', 'like', $territory.'%');
        }

        return response()->json($data->get());
    }

    public function getAnimalsGodfather($territory = 0, $specie = 0): JsonResponse
    {
        $data = Process::query()->select(['processes.id', 'processes.name', 'specie', 'images', 'created_at', 'district.name as district', 'county.name as county'])
            ->join('territories as district', 'district.id', '=', DB::raw('LEFT(territory_id, 2)'))
            ->join('territories as county', 'county.id', '=', DB::raw('LEFT(territory_id, 4)'))
            ->where('status', 'waiting_godfather')
            ->where('processes.urgent', 1)
            ->orderBy('created_at', 'desc')
            ->limit(20);

        // Specie
        if ($specie) {
            $data = $data->where('specie', $specie);
        }

        // Territory
        if ($territory > 0) {
            $territory = str_pad($territory, 2, 0, STR_PAD_LEFT);
            $data = $data->where('territory_id', 'like', $territory.'%');
        }

        return response()->json($data->get());
    }

    public function subscribeNewsletter(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        $validator->validate();

        // Check if subscribed
        if (Newsletter::isSubscribed($request->email)) {
            $validator->errors()->add('email', __('Your email is already subscribed.'));
            throw new ValidationException($validator);
        }

        // Subscribe
        Newsletter::subscribe($request->email, [
            'FNAME' => strtok($request->email, '@'),
        ]);

        // Check if success
        if (! Newsletter::lastActionSucceeded()) {
            Log::info(Newsletter::getApi()->getLastError());

            $validator->errors()->add('email', __('Something went wrong, please try again later.'));
            throw new ValidationException($validator);
        }

        return response()->json([
            'errors' => false,
            'message' => __('Thank you for subscribing to our newsletter.'),
        ]);
    }

    public function login(): Application|Redirector|RedirectResponse
    {
        $result = Auth::guard(backpack_guard_name())->attempt(request()->only('email', 'password'));

        return redirect('/friends')->with('login', $result);
    }

    public function logout(): Application|Redirector|RedirectResponse
    {
        Auth::guard(backpack_guard_name())->logout();

        return redirect('/friends')->with('logout', true);
    }
}
