<?php

namespace App\Http\Controllers\Admin\Traits;

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
use App\Models\Treatment;
use Cache;
use DB;

trait LocalCache
{
    public static function page($slug, $locale)
    {
        return Cache::rememberForever("page_{$slug}_{$locale}", function () use ($slug) {
            $page = Page::findBySlug($slug);

            if (!$page) {
                abort(404);
            }

            return [
                'title' => config('app.name') . ' - ' . $page->title,
                'page' => $page->withFakes(),
            ];
        });
    }

    public static function treated()
    {
        return Cache::rememberForever('treatments_affected_animals_new', function () {
            return Treatment::selectRaw('SUM(affected_animals_new) as total')->where('status', 'approved')->first()->total;
        });
    }

    public static function adopted()
    {
        return Cache::rememberForever('adoptions_count', function () {
            return Adoption::selectRaw('COUNT(processed) as total')->where('processed', 0)->first()->total;
        });
    }

    public static function headquarters_territories_acting()
    {
        return Cache::rememberForever('headquarters_territories_acting', function () {
            $activeHeadquarters = Headquarter::active()->pluck('id');

            $territoriesRaw = DB::table('headquarters_territories')
                ->join('territories', function ($query) {
                    $query->on('territories.id', 'LIKE', DB::raw('CONCAT(headquarters_territories.territory_id, "%")'))
                        ->orOn('territories.id', '=', DB::raw('LEFT(headquarters_territories.territory_id, 4)'))
                        ->orOn('territories.id', '=', DB::raw('LEFT(headquarters_territories.territory_id, 2)'));
                })
                ->whereIn('headquarter_id', $activeHeadquarters)
                ->select(['id', 'name', 'parent_id', 'headquarter_id'])
                ->groupBy('id')
                ->orderByRaw('LENGTH(id), id')
                ->get();

            $territories = [
                0 => [], // Districts
                1 => [], // County
                2 => [], // Parish
            ];

            foreach ($territoriesRaw as $value) {
                $territories[strlen($value->id) / 2 - 1][] = (array) $value;
            }

            return $territories;
        });
    }

    public static function territories_form_all()
    {
        return Cache::rememberForever('territories_form_all', function () {
            return [
                0 => Territory::select(['id', 'name'])->where('level', 1)->get()->toArray(), // Districts
                1 => Territory::select(['id', 'name', 'parent_id'])->where('level', 2)->get()->toArray(), // County
            ];
        });
    }

    public static function campaigns()
    {
        return Cache::rememberForever('campaigns', function () {
            return Campaign::select(['name', 'introduction', 'description', 'image'])
                ->orderBy('lft', 'asc')
                ->get();
        });
    }

    public static function products()
    {
        return Cache::rememberForever('products', function () {
            return request()->secure() ? app('App\Http\Controllers\WooCommerceController')->getProducts() : [];
        });
    }

    public static function headquarters()
    {
        return Cache::rememberForever('headquarters', function () {
            return Headquarter::select(['id', 'name', 'address', 'phone', 'mail'])->where('active', 1)->get();
        });
    }

    public static function processes_districts_godfather()
    {
        return Cache::rememberForever('processes_districts_godfather', function () {
            return Process::select(['territories.id', 'territories.name'])
                ->join('territories', 'territories.id', '=', DB::raw('LEFT(territory_id, 2)'))
                ->where('status', 'waiting_godfather')
                ->orderBy('territories.id', 'asc')
                ->distinct()
                ->get();
        });
    }

    public static function adoptions_districts_adoption()
    {
        return Cache::rememberForever('adoptions_districts_adoption', function () {
            return Adoption::select(['territories.id', 'territories.name'])
                ->join('processes', 'processes.id', '=', 'adoptions.process_id')
                ->join('territories', 'territories.id', '=', DB::raw('LEFT(territory_id, 2)'))
                ->where('adoptions.status', 'open')
                ->orderBy('territories.id', 'asc')
                ->distinct()
                ->get();
        });
    }

    public static function sponsors()
    {
        return Cache::rememberForever('sponsors', function () {
            return Sponsor::select(['name', 'url', 'image'])
                ->orderBy('lft')
                ->get();
        });
    }

    public static function friend_card_modalities()
    {
        return Cache::rememberForever('friend_card_modalities', function () {
            return FriendCardModality::select(['name', 'description', 'paypal_code', 'amount', 'type'])
                ->where('amount', '>', 0)
                ->orderBy('id', 'asc')
                ->get();
        });
    }

    public static function partners()
    {
        return Cache::rememberForever('partners', function () {
            $partners = Partner::select(['id', 'name', 'image', 'benefit', 'email', 'url', 'facebook', 'instagram', 'phone1', 'phone1_info', 'phone2', 'phone2_info', 'address', 'address_info', 'promo_code'])
                ->with([
                    'territories' => function ($query) {
                        $query->select(['id', 'name']);
                    },
                    'categories' => function ($query) {
                        $query->select(['id', 'name']);
                    }])
                ->orderBy('updated_at', 'DESC')
                ->where('status', 1)
                ->get();

            foreach ($partners as $partner) {
                $partner->categories = $partner->categories->pluck('id')->toArray();
                $partner->territories = $partner->territories->pluck('id')->toArray();
            }

            return $partners;
        });
    }

    public static function partners_categories()
    {
        return Cache::rememberForever('partners_categories', function () {
            return PartnerCategory::select(['id', 'name', 'description'])
                ->whereIn('id', function ($query) {
                    $query->select('partner_category_list_id')
                        ->distinct()
                        ->from('partners_categories');
                })
                ->get();
        });
    }

    public static function partners_territories()
    {
        return Cache::rememberForever('partners_territories', function () {
            return Territory::select(['id', 'name'])
                ->whereIn('id', function ($query) {
                    $query->select('territory_id')
                        ->distinct()
                        ->from('partners_territories');
                })
                ->get();
        });
    }

    public static function processes_urgent()
    {
        return Cache::rememberForever('processes_urgent', function () {
            return Process::select(['id', 'name', 'address', 'specie', 'history', 'latlong', 'images', 'created_at', 'updated_at'])
                ->where('status', 'waiting_godfather')
                ->where('urgent', 1)
                ->orderBy('created_at', 'desc')
                ->limit(9)
                ->get();
        });
    }

}
