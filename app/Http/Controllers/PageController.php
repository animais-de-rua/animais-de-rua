<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\FriendCardModality;
use App\Models\Headquarter;
use App\Models\Page;
use App\Models\Partner;
use App\Models\PartnerCategory;
use App\Models\Process;
use App\Models\Territory;

class PageController extends Controller
{
    public function index($slug)
    {
        \Debugbar::disable();

        $page = Page::findBySlug($slug);

        if (!$page) {
            abort(404);
        }

        $this->data = [
            'title' => $page->title,
            'page' => $page->withFakes()
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
                'total_operations' => 12345
            ];
        }

        return $data;
    }

    private function home()
    {
        $processes = Process::select(['name', 'specie', 'history', 'images', 'status', 'created_at'])
            ->where('status', 'waiting_godfather')
            ->orderBy('created_at', 'desc')
            ->limit(9)
            ->get();

        $campaigns = Campaign::select(['name', 'introduction', 'description', 'image'])
            ->orderBy('lft', 'asc')
            ->get();

        return [
            'processes' => $processes,
            'campaigns' => $campaigns
        ];
    }

    private function association()
    {
        $headquarters = Headquarter::select(['name', 'mail'])->get();

        return [
            'headquarters' => $headquarters
        ];
    }

    private function ced()
    {
        return [];
    }

    private function animals()
    {
        return [];
    }

    private function help()
    {
        return [];
    }

    private function partners()
    {
        return [];
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
                'territories' => $partner_territories
            ]
        ];
    }
}
