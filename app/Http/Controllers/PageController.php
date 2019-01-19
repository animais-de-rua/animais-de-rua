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
use App\Models\Treatment;
use Cache;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Newsletter;
use Validator;

class PageController extends Controller
{
    public function index($slug = 'home')
    {
        \Debugbar::disable();

        $locale = \Session::get('locale');

        $this->data = Cache::rememberForever("page_{$slug}_{$locale}", function () use ($slug) {
            $page = Page::findBySlug($slug);

            if (!$page) {
                abort(404);
            }

            return [
                'title' => $page->title,
                'page' => $page->withFakes(),
            ];
        });

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
            $treated = Cache::rememberForever('treatments_affected_animals_new', function () {
                $total = Treatment::selectRaw('SUM(affected_animals_new) as total')->where('status', 'approved')->first()->total;
                return $total;
            });

            $adopted = Cache::rememberForever('adoptions_count', function () {
                $total = Adoption::selectRaw('COUNT(processed) as total')->where('processed', 0)->first()->total;
                return $total;
            });

            $form_acting_territories = Cache::rememberForever('headquarters_territories_acting', function () {
                $territoriesRaw = DB::table('headquarters_territories')
                    ->join('territories', function ($query) {
                        $query->on('territories.id', 'LIKE', DB::raw('CONCAT(headquarters_territories.territory_id, "%")'))
                            ->orOn('territories.id', '=', DB::raw('LEFT(headquarters_territories.territory_id, 4)'))
                            ->orOn('territories.id', '=', DB::raw('LEFT(headquarters_territories.territory_id, 2)'));
                    })
                    ->select(['id', 'name', 'parent_id'])
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

            $form_all_territories = Cache::rememberForever('territories_form_all', function () {
                return [
                    0 => Territory::select(['id', 'name'])->where('level', 1)->get()->toArray(), // Districts
                    1 => Territory::select(['id', 'name', 'parent_id'])->where('level', 2)->get()->toArray(), // County
                ];
            });

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
        $campaigns = Cache::rememberForever('campaigns', function () {
            return Campaign::select(['name', 'introduction', 'description', 'image'])
                ->orderBy('lft', 'asc')
                ->get();
        });

        $products = Cache::rememberForever('products', function () {
            return app('App\Http\Controllers\PrestaShopController')->getProducts();
        });

        return [
            'processes' => $this->_urgent_help(),
            'campaigns' => $campaigns,
            'products' => $products,
        ];
    }

    private function association()
    {
        $headquarters = Cache::rememberForever('headquarters', function () {
            return Headquarter::select(['name', 'mail'])->get();
        });

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
        $districts_godfather = Cache::rememberForever('processes_districts_godfather', function () {
            return Process::select(['territories.id', 'territories.name'])
                ->join('territories', 'territories.id', '=', DB::raw('LEFT(territory_id, 2)'))
                ->where('status', 'waiting_godfather')
                ->orderBy('territories.id', 'asc')
                ->distinct()
                ->get();
        });

        $districts_adoption = Cache::rememberForever('adoptions_districts_adoption', function () {
            return Adoption::select(['territories.id', 'territories.name'])
                ->join('processes', 'processes.id', '=', 'adoptions.process_id')
                ->join('territories', 'territories.id', '=', DB::raw('LEFT(territory_id, 2)'))
                ->where('adoptions.status', 'open')
                ->orderBy('territories.id', 'asc')
                ->distinct()
                ->get();
        });

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
        $sponsors = Cache::rememberForever('sponsors', function () {
            return Sponsor::select(['name', 'url', 'image'])
                ->orderBy('lft')
                ->get();
        });

        return [
            'sponsors' => $sponsors,
        ];
    }

    private function friends()
    {
        $modalities = Cache::rememberForever('friend_card_modalities', function () {
            return FriendCardModality::select(['name', 'description', 'paypal_code', 'amount', 'type'])
                ->orderBy('id', 'asc')
                ->get();
        });

        $partners = Cache::rememberForever('partners', function () {
            $partners = Partner::select(['id', 'name', 'image', 'benefit', 'email', 'url', 'facebook', 'phone1', 'phone1_info', 'phone2', 'phone2_info', 'address', 'address_info'])
                ->with('territories', 'categories')
                ->orderBy('updated_at', 'DESC')
                ->get();

            foreach ($partners as $partner) {
                $partner->categories = $partner->categories->pluck('id')->toArray();
                $partner->territories = $partner->territories->pluck('id')->toArray();
            }

            return $partners;
        });

        $partner_categories = Cache::rememberForever('partner_categories', function () {
            return PartnerCategory::select(['id', 'name'])
                ->whereIn('id', function ($query) {
                    $query->select('partner_category_list_id')
                        ->distinct()
                        ->from('partners_categories');
                })
                ->get();
        });

        $partner_territories = Cache::rememberForever('partners_territories', function () {
            return Territory::select(['id', 'name'])
                ->whereIn('id', function ($query) {
                    $query->select('territory_id')
                        ->distinct()
                        ->from('partners_territories');
                })
                ->get();
        });

        return [
            'subscribed' => isset($_GET['success']),
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
        $processes = Cache::rememberForever('processes_urgent', function () {
            return Process::select(['id', 'name', 'images'])
                ->where('status', 'waiting_godfather')
                ->where('urgent', 1)
                ->orderBy('created_at', 'desc')
                ->limit(9)
                ->get();
        });

        return $processes;
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

        Newsletter::subscribe($request->email);

        // Check if subscribed
        if (Newsletter::isSubscribed($request->email)) {
            $validator->errors()->add('email', __('Your email is already subscribed.'));
            throw new ValidationException($validator);
        }

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
}
