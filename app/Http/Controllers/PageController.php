<?php

namespace App\Http\Controllers;

use App\Helpers\EnumHelper;
use App\Http\Controllers\Admin\Traits\LocalCache;
use App\Http\Controllers\Controller;
use App\Models\Adoption;
use App\Models\Page;
use App\Models\Process;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Newsletter;
use Validator;

class PageController extends Controller
{
    use LocalCache;

    public function index($slug = 'home')
    {
        // \Debugbar::disable();

        $locale = \Session::get('locale');

        $this->data = LocalCache::page($slug, $locale);

        // Common data to all pages
        $this->data = array_merge($this->data, $this->common());

        // Page specific data
        if (method_exists($this, $slug)) {
            $this->data = array_merge($this->data, call_user_func(array($this, $slug)));
        }

        return view('pages.' . $this->data['page']->template, $this->data);
    }

    public function blank()
    {
        return view('pages.blank', $this->common());
    }

    private function common()
    {
        $data = [];

        if (\Request::ajax()) {
            $data = [];
        } else {
            $treated = LocalCache::treated();
            $adopted = LocalCache::adopted();
            $form_acting_territories = LocalCache::headquarters_territories_acting();
            $form_all_territories = LocalCache::territories_form_all();

            $base_counter = \Config::get('settings.base_counter', 0);

            $data = [
                'total_interventions' => $base_counter + $treated + $adopted,
                'form_acting_territories' => $form_acting_territories,
                'form_all_territories' => $form_all_territories,
            ];
        }

        return $data;
    }

    private function home()
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

    private function association()
    {
        $headquarters = LocalCache::headquarters();

        return [
            'headquarters' => $headquarters,
        ];
    }

    private function ced()
    {
        return [];
    }

    private function animals()
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

    public function animalsView($option, $id)
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
                $animal = Adoption::select(['adoptions.name', 'adoptions.history', 'specie', 'adoptions.images', 'adoptions.created_at', 'district.id as district_id', 'district.name as district', 'county.name as county'])
                    ->join('processes', 'processes.id', '=', 'adoptions.process_id')
                    ->join('territories as district', 'district.id', '=', DB::raw('LEFT(territory_id, 2)'))
                    ->join('territories as county', 'county.id', '=', DB::raw('LEFT(territory_id, 4)'))
                    ->where('adoptions.id', $id)
                    ->firstOrFail()->toArray();

                $district = $animal['district_id'];

                $other = Adoption::select(['adoptions.id', 'adoptions.name', 'adoptions.history', 'specie', 'adoptions.images', 'adoptions.created_at'])
                    ->join('processes', 'processes.id', '=', 'adoptions.process_id')
                    ->where('adoptions.id', '<>', $id)
                    ->where(DB::raw('LEFT(territory_id, 2)'), $district)
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

    private function help()
    {
        return [];
    }

    private function partners()
    {
        $sponsors = LocalCache::sponsors();

        return [
            'sponsors' => $sponsors,
        ];
    }

    private function friends()
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
    public function getAnimalsAdoption($territory = 0, $specie = 0)
    {
        $data = Adoption::select(['adoptions.id', 'adoptions.name', 'specie', 'adoptions.images', 'adoptions.created_at', 'district.name as district', 'county.name as county'])
            ->join('processes', 'processes.id', '=', 'adoptions.process_id')
            ->join('territories as district', 'district.id', '=', DB::raw('LEFT(territory_id, 2)'))
            ->join('territories as county', 'county.id', '=', DB::raw('LEFT(territory_id, 4)'))
            ->where('adoptions.status', 'open')
            ->orderBy('created_at', 'desc')
            ->limit(20);

        // Specie
        if ($specie) {
            $data = $data->where('specie', $specie);
        }

        // Territory
        if ($territory > 0) {
            $territory = str_pad($territory, 2, 0, STR_PAD_LEFT);
            $data = $data->where('territory_id', 'like', $territory . '%');
        }

        return response()->json($data->get());
    }

    public function getAnimalsGodfather($territory = 0, $specie = 0)
    {
        $data = Process::select(['processes.id', 'processes.name', 'specie', 'images', 'created_at', 'district.name as district', 'county.name as county'])
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
            $data = $data->where('territory_id', 'like', $territory . '%');
        }

        return response()->json($data->get());
    }

    public function subscribeNewsletter(Request $request)
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
        Newsletter::subscribe($request->email);

        // Check if success
        if (!Newsletter::lastActionSucceeded()) {
            $validator->errors()->add('email', __('Something went wrong, please try again later.'));
            throw new ValidationException($validator);
        }

        return response()->json([
            'errors' => false,
            'message' => __('Thank you for subscribing to our newsletter.'),
        ]);
    }

    public function login()
    {
        $result = \Auth::guard(backpack_guard_name())->attempt(request()->only('email', 'password'));
        return redirect('/friends')->with('login', $result);
    }

    public function logout()
    {
        \Auth::guard(backpack_guard_name())->logout();
        return redirect('/friends')->with('logout', true);
    }
}
