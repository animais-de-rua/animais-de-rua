<?php

namespace App\Http\Controllers;

use App\Helpers\EnumHelper;
use App\Http\Controllers\Controller;
use App\Models\Adoption;
use App\Models\Campaign;
use App\Models\FriendCardModality;
use App\Models\Headquarter;
use App\Models\Page;
use App\Models\Partner;
use App\Models\PartnerCategory;
use App\Models\Process;
use App\Models\Sponsor;
use App\Models\Territory;

class PageController extends Controller
{
    public function index($slug = 'home')
    {
        \Debugbar::disable();

        $page = Page::findBySlug($slug);

        if (!$page) {
            abort(404);
        }

        $this->data = [
            'title' => $page->title,
            'page' => $page->withFakes(),
        ];

        // Common data to all pages
        $this->data = array_merge($this->data, $this->common());

        // Page specific data
        if (method_exists($this, $slug)) {
            $this->data = array_merge($this->data, call_user_func(array($this, $slug)));
        }

        return view('pages.' . $page->template, $this->data);
    }

    private function common()
    {
        $data = [];

        if (\Request::ajax()) {
            $data = [];
        } else {
            $data = [
                'total_operations' => 12345,
            ];
        }

        return $data;
    }

    private function home()
    {
        $campaigns = Campaign::select(['name', 'introduction', 'description', 'image'])
            ->orderBy('lft', 'asc')
            ->get();

        return [
            'processes' => $this->_urgent_help(),
            'campaigns' => $campaigns,
        ];
    }

    private function association()
    {
        $headquarters = Headquarter::select(['name', 'mail'])->get();

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
        $districts_godfather = Process::select(['territories.id', 'territories.name'])
            ->join('territories', 'territories.id', '=', \DB::raw('LEFT(territory_id, 2)'))
            ->where('status', 'waiting_godfather')
            ->orderBy('territories.id', 'asc')
            ->distinct()
            ->get();

        $districts_adoption = Adoption::select(['territories.id', 'territories.name'])
            ->join('processes', 'processes.id', '=', 'adoptions.process_id')
            ->join('territories', 'territories.id', '=', \DB::raw('LEFT(territory_id, 2)'))
            ->where('adoptions.status', 'open')
            ->orderBy('territories.id', 'asc')
            ->distinct()
            ->get();

        $species = EnumHelper::translate('process.specie');

        return [
            'processes' => $this->_urgent_help(),
            'species' => $species,
            'districts' => [
                'godfather' => $districts_godfather,
                'adoption' => $districts_adoption,
            ],
        ];
    }

    private function help()
    {
        return [];
    }

    private function partners()
    {
        $sponsors = Sponsor::select(['name', 'url', 'image'])
            ->orderBy('lft')
            ->get();

        return [
            'sponsors' => $sponsors,
        ];
    }

    private function friends()
    {
        $modalities = FriendCardModality::select(['name', 'description', 'paypal_code', 'amount', 'type'])
            ->orderBy('id', 'asc')
            ->get();

        $partners = Partner::select(['id', 'name', 'image', 'benefit', 'email', 'url', 'facebook', 'phone1', 'phone1_info', 'phone2', 'phone2_info', 'address', 'address_info'])
            ->with('territories', 'categories')
            ->orderBy('updated_at', 'DESC')
            ->get();

        foreach ($partners as $partner) {
            $partner->categories = $partner->categories->pluck('id')->toArray();
            $partner->territories = $partner->territories->pluck('id')->toArray();
        }

        $partner_categories = PartnerCategory::select(['id', 'name'])
            ->whereIn('id', function ($query) {
                $query->select('partner_category_list_id')
                    ->distinct()
                    ->from('partners_categories');
            })
            ->get();

        $partner_territories = Territory::select(['id', 'name'])
            ->whereIn('id', function ($query) {
                $query->select('territory_id')
                    ->distinct()
                    ->from('partners_territories');
            })
            ->get();

        return [
            'modalities' => $modalities,
            'partners' => [
                'list' => $partners,
                'categories' => $partner_categories,
                'territories' => $partner_territories,
            ],
        ];
    }

    private function _urgent_help()
    {
        return Process::select(['name', 'specie', 'history', 'images', 'status', 'urgent', 'created_at'])
            ->where('status', 'waiting_godfather')
            ->orderBy('urgent', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();
    }

    // API
    public function getAnimalsAdoption($territory = 0, $specie = 0)
    {
        $data = Adoption::select(['adoptions.id', 'adoptions.name', 'specie', 'adoptions.images', 'adoptions.created_at', 'district.name as district', 'county.name as county'])
            ->join('processes', 'processes.id', '=', 'adoptions.process_id')
            ->join('territories as district', 'district.id', '=', \DB::raw('LEFT(territory_id, 2)'))
            ->join('territories as county', 'county.id', '=', \DB::raw('LEFT(territory_id, 4)'))
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
            ->join('territories as district', 'district.id', '=', \DB::raw('LEFT(territory_id, 2)'))
            ->join('territories as county', 'county.id', '=', \DB::raw('LEFT(territory_id, 4)'))
            ->where('status', 'waiting_godfather')
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
}
