<?php

namespace App\Http\Controllers\Admin\Traits;

use App\Http\Controllers\WooCommerceController;
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
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait LocalCache
{
    public static function page($slug, $locale): array
    {
        return Cache::rememberForever("page_{$slug}_{$locale}", function () use ($slug) {
            $page = Page::findBySlug($slug);

            if (! $page) {
                abort(404);
            }

            return [
                'title' => config('app.name').' - '.$page->title,
                'page' => $page->withFakes(),
            ];
        });
    }

    public static function treated(): int
    {
        return Cache::rememberForever('treatments_affected_animals_new', function () {
            return Treatment::selectRaw('SUM(affected_animals_new) as total')->where('status', 'approved')->first()->total;
        });
    }

    public static function adopted(): int
    {
        return Cache::rememberForever('adoptions_count', function () {
            return Adoption::selectRaw('COUNT(processed) as total')->where('processed', 0)->first()->total;
        });
    }

    public static function headquarters_territories_acting(): array
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
                ->selectRaw('ANY_VALUE(`id`) id, ANY_VALUE(`name`) name, ANY_VALUE(`parent_id`) parent_id, ANY_VALUE(`headquarter_id`) headquarter_id')
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

    public static function territories_form_all(): array
    {
        return Cache::rememberForever('territories_form_all', function () {
            return [
                0 => Territory::select(['id', 'name'])->where('level', 1)->get()->toArray(), // Districts
                1 => Territory::select(['id', 'name', 'parent_id'])->where('level', 2)->get()->toArray(), // County
            ];
        });
    }

    public static function campaigns(): Collection
    {
        return Cache::rememberForever('campaigns', function () {
            return Campaign::select(['name', 'introduction', 'description', 'image'])
                ->orderBy('lft', 'asc')
                ->get();
        });
    }

    public static function products(): array
    {
        return Cache::rememberForever('products', function () {
            return request()->secure() ? app(WooCommerceController::class)->getProducts() : [];
        });
    }

    public static function headquarters(): Collection
    {
        return Cache::rememberForever('headquarters', function () {
            return Headquarter::select(['id', 'name', 'address', 'phone', 'mail'])->where('active', 1)->get();
        });
    }

    public static function processes_districts_godfather(): Collection
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

    public static function adoptions_districts_adoption(): Collection
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

    public static function sponsors(): Collection
    {
        return Cache::rememberForever('sponsors', function () {
            return Sponsor::select(['name', 'url', 'image'])
                ->orderBy('lft')
                ->get();
        });
    }

    public static function friend_card_modalities(): Collection
    {
        return Cache::rememberForever('friend_card_modalities', function () {
            return FriendCardModality::select(['name', 'description', 'paypal_code', 'amount', 'type', 'visible'])
                ->where('visible', 1)
                ->orderBy('id', 'asc')
                ->get();
        });
    }

    public static function partners(): Collection
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

    public static function partners_categories(): Collection
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

    public static function partners_territories(): Collection
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

    public static function processes_urgent(): Collection
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

    public static function petsitters(): Collection
    {
        return Cache::rememberForever('petsitters', function () {
            return User::with(['headquarters:id,name'])
                ->select([
                    'users.id',
                    'users.name',
                    'users.petsitting_role',
                    'users.petsitting_description',
                    'users.petsitting_image',
                ])
                ->where('users.status', 1)
                ->whereIn('users.id', function ($query) {
                    $query->select('model_id')
                        ->from('user_has_roles')
                        ->where('role_id', 2);
                })
                ->whereNotNull('users.petsitting_role')
                ->orderBy('users.name', 'asc')
                ->get();
        });
    }
}
