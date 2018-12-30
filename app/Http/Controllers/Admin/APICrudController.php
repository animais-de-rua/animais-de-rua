<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\Permissions;
use App\Models\Adoption;
use App\Models\Appointment;
use App\Models\Fat;
use App\Models\FriendCardModality;
use App\Models\Godfather;
use App\Models\Headquarter;
use App\Models\PartnerCategory;
use App\Models\Process;
use App\Models\Protocol;
use App\Models\Territory;
use App\Models\TreatmentType;
use App\Models\Vet;
use App\User as UserBase;
use Backpack\Base\app\Models\BackpackUser as User;
use DB;
use Illuminate\Http\Request;

class APICrudController extends CrudController
{
    use Permissions;

    public function ajax()
    {
        $args = func_get_args();
        return call_user_func_array([$this, $args[0] . $args[1]], array_slice($args, 2));
    }

    /*
    |--------------------------------------------------------------------------
    | Default Search
    |--------------------------------------------------------------------------
    */
    public function getSearchParam()
    {
        $request = request();
        return $request ? ($request->has('q') || $request->has('term') ? $request->input('q') . $request->input('term') : false) : false;
    }

    public function entitySearch($entity, $searchFields = null, $where = null, $whereIn = null)
    {
        $search_term = $this->getSearchParam();
        $results = $entity::select();

        if ($search_term && count($searchFields)) {
            $results = $entity::where(function ($query) use ($search_term, $searchFields) {
                $query->where(array_shift($searchFields), 'LIKE', "%$search_term%");

                foreach ($searchFields as $field) {
                    $query->orWhere($field, 'LIKE', "%$search_term%");
                }
            });
        }

        if ($where) {
            foreach ($where as $key => $value) {
                $results = $results->where($key, $value);
            }
        }

        if ($whereIn) {
            foreach ($whereIn as $key => $value) {
                $results = $results->whereIn($key, $value);
            }
        }

        return $results->paginate(10);
    }

    /*
    |--------------------------------------------------------------------------
    | Adoption
    |--------------------------------------------------------------------------
    */
    public function adoptionSearch()
    {
        return $this->entitySearch(Adoption::class, ['name', 'name_after']);
    }

    public function adoptionFilter()
    {
        return $this->adoptionSearch()->pluck('name', 'id');
    }

    /*
    |--------------------------------------------------------------------------
    | Appointment
    |--------------------------------------------------------------------------
    */
    public function appointmentSearch()
    {
        $searchFields = ['id', 'date_1', 'date_2'];
        $search_term = $this->getSearchParam();

        $results = Appointment::with(['user', 'process.headquarter'])->where('status', '<>', 'approving');

        if ($search_term) {
            $results = $results->where(function ($query) use ($search_term) {
                $query->where('id', 'LIKE', "%$search_term%")
                    ->orWhere('date_1', 'LIKE', "%$search_term%")
                    ->orWhere('date_2', 'LIKE', "%$search_term%");
            });
        }

        if (!is('admin', 'appointments')) {
            $results = $results->where('user_id', backpack_user()->id);
        }

        return $results->orderBy('id', 'DESC')->paginate(10);
    }

    public function appointmentFilter()
    {
        return $this->appointmentSearch()->pluck('detail', 'id');
    }

    /*
    |--------------------------------------------------------------------------
    | Treatment Type
    |--------------------------------------------------------------------------
    */
    public function friendCardModalitiesList()
    {
        return FriendCardModality::get()->pluck('value', 'id')->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | Godfather
    |--------------------------------------------------------------------------
    */
    public function godfatherSearch()
    {
        $headquarters = restrictToHeadquarters();
        $whereIn = $headquarters ? ['headquarter_id' => $headquarters] : [];

        return $this->entitySearch(Godfather::class, ['name', 'email'], null, $whereIn);
    }

    public function godfatherFilter()
    {
        return $this->godfatherSearch()->pluck('name', 'id');
    }

    /*
    |--------------------------------------------------------------------------
    | Fat
    |--------------------------------------------------------------------------
    */
    public function fatSearch()
    {
        $headquarters = restrictToHeadquarters();
        $whereIn = $headquarters ? ['headquarter_id' => $headquarters] : [];

        return $this->entitySearch(Fat::class, ['name', 'email'], null, $whereIn);
    }

    public function fatFilter()
    {
        return $this->godfatherSearch()->pluck('name', 'id');
    }

    /*
    |--------------------------------------------------------------------------
    | Headquarter
    |--------------------------------------------------------------------------
    */
    public function headquarterSearch()
    {
        return $this->entitySearch(Headquarter::class, ['name']);
    }

    public function headquarterFilter()
    {
        return $this->headquarterSearch()->pluck('name', 'id');
    }

    public function headquarterList()
    {
        return $this->headquarterFilter()->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | Partner
    |--------------------------------------------------------------------------
    */
    public function partnerCategorySearch()
    {
        return $this->entitySearch(PartnerCategory::class, ['name']);
    }

    public function partnerCategoryFilter()
    {
        return $this->partnerCategorySearch()->pluck('name', 'id');
    }

    public function partnerCategoryList()
    {
        return $this->partnerCategoryFilter()->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | Protocol
    |--------------------------------------------------------------------------
    */
    public function protocolSearch()
    {
        return $this->entitySearch(Protocol::class, ['name']);
    }

    public function protocolFilter()
    {
        return $this->protocolSearch()->pluck('name', 'id');
    }

    public function protocolList()
    {
        return $this->protocolFilter()->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | Process
    |--------------------------------------------------------------------------
    */
    public function processSearch()
    {
        $search_term = $this->getSearchParam();

        $results = Process::with('headquarter')->whereIn('status', ['waiting_godfather', 'waiting_capture', 'open']);

        // Headquarter filter
        $headquarters = restrictToHeadquarters();
        if ($headquarters) {
            $results = $results->whereIn('headquarter_id', $headquarters);
        }

        // Search
        if ($search_term) {
            $results = $results->where(function ($query) use ($search_term) {
                $query->where('id', "$search_term")
                    ->orWhere('name', 'LIKE', "%$search_term%");
            });
        }

        return $results->paginate(10);
    }

    public function processFilter()
    {
        return $this->processSearch()->pluck('name', 'id');
    }

    /*
    |--------------------------------------------------------------------------
    | User
    |--------------------------------------------------------------------------
    */
    public function userSearch($role = UserBase::ALL)
    {
        $search_term = $this->getSearchParam();

        $roles = [];
        if ($role & UserBase::ADMIN) {
            $roles[] = 1;
        }

        if ($role & UserBase::VOLUNTEER) {
            $roles[] = 2;
        }

        if ($role & UserBase::FAT) {
            $roles[] = 3;
        }

        $users = User::whereIn('id', DB::table('user_has_roles')->select('model_id')->whereIn('role_id', $roles));

        $headquarters = restrictToHeadquarters();
        if ($headquarters) {
            $users = $users->whereIn('headquarter_id', $headquarters);
        }

        if ($search_term) {
            $users = $users->where(function ($query) use ($search_term) {
                $query->where('name', 'LIKE', "%$search_term%")
                    ->orWhere('email', 'LIKE', "%$search_term%");
            });
        }

        return $users->paginate(10);
    }

    public function userFilter($role = UserBase::ALL)
    {
        return $this->userSearch($role)->pluck('name', 'id');
    }

    /*
    |--------------------------------------------------------------------------
    | Vet
    |--------------------------------------------------------------------------
    */
    public function vetSearch()
    {
        return $this->entitySearch(Vet::class, ['name']);
    }

    public function vetFilter()
    {
        return $this->vetSearch()->pluck('name', 'id');
    }

    /*
    |--------------------------------------------------------------------------
    | Treatment Type
    |--------------------------------------------------------------------------
    */
    public function treatmentTypeList()
    {
        return TreatmentType::get()->pluck('name', 'id')->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | Territory
    |--------------------------------------------------------------------------
    */
    private static $territoryCache = [];

    public function territorySearch($level = Territory::ALL)
    {
        $search_term = $this->getSearchParam();
        $data = [];

        if (isset(self::$territoryCache[$level . $search_term])) {
            return self::$territoryCache[$level . $search_term];
        }

        if ($level & Territory::DISTRITO) {
            $dataC = DB::table('territories as a')
                ->select(DB::raw('a.id, a.name'));

            $where = [['a.level', '=', 1]];
            if ($search_term) {
                array_push($where, ['a.name', 'LIKE', "%$search_term%"]);
                $dataC = $dataC->limit(2);
            }

            foreach ($dataC->where($where)->get() as $v) {
                $data[] = $v;
            }

        }

        if ($level & Territory::CONCELHO) {
            $dataB = DB::table('territories as a')
                ->join('territories as b', 'a.parent_id', '=', 'b.id')
                ->select(DB::raw("a.id, CONCAT(a.name, ', ', b.name) name"));

            $where = [['a.level', '=', 2]];
            if ($search_term) {
                array_push($where, ['a.name', 'LIKE', "%$search_term%"]);
                $dataB = $dataB->limit(4);
            }

            foreach ($dataB->where($where)->get() as $v) {
                $data[] = $v;
            }

        }

        if ($level & Territory::FREGUESIA) {
            $dataA = DB::table('territories as a')
                ->join('territories as b', 'a.parent_id', '=', 'b.id')
                ->join('territories as c', 'b.parent_id', '=', 'c.id')
                ->select(DB::raw("a.id, CONCAT(a.name, ', ', b.name, ', ', c.name) name"));

            $where = [['a.level', '=', 3]];
            if ($search_term) {
                array_push($where, ['a.name', 'LIKE', "%$search_term%"]);
                $dataA = $dataA->limit(8);
            }

            foreach ($dataA->where($where)->get() as $v) {
                $data[] = $v;
            }

        }

        $data = Territory::hydrate($data);

        self::$territoryCache[$level . $search_term] = $data;

        return $data;
    }

    public function territoryFilter($level = Territory::ALL)
    {
        $data = [];

        foreach ($this->territorySearch($level) as $elem) {
            $data[$elem->id] = $elem->name;
        }

        return $data;
    }

    public function territoryList($level = Territory::ALL)
    {
        return $this->territoryFilter($level);
    }

    /*
    |--------------------------------------------------------------------------
    | Acting Territories
    |--------------------------------------------------------------------------
    */

    public function actingTerritorySearch($level = Territory::ALL)
    {
        $search_term = $this->getSearchParam();

        $territories = DB::table('headquarters_territories')
            ->join('territories as a', function ($query) use ($search_term) {
                $query->on('a.id', 'LIKE', DB::raw('CONCAT(headquarters_territories.territory_id, "%")'))
                    ->orOn('a.id', '=', DB::raw('LEFT(headquarters_territories.territory_id, 4)'))
                    ->orOn('a.id', '=', DB::raw('LEFT(headquarters_territories.territory_id, 2)'));

                if ($search_term) {
                    $query->where('name', 'LIKE', "%$search_term%");
                }
            })
            ->leftJoin('territories as b', 'a.parent_id', '=', 'b.id')
            ->leftJoin('territories as c', 'b.parent_id', '=', 'c.id')
            ->selectRaw('a.id, CONCAT(a.name, IF(b.name is null, "", CONCAT(", ", b.name)), IF(c.name is null, "", CONCAT(", ", c.name))) as name, a.parent_id')
            ->groupBy('a.id')
            ->orderByRaw('LENGTH(a.id), a.id');

        $territories = $territories->where(function ($query) use ($level) {
            if ($level & Territory::FREGUESIA) {
                $query->whereRaw('LENGTH(a.id) = 6');
            }

            if ($level & Territory::CONCELHO) {
                $query->orWhereRaw('LENGTH(a.id) = 4');
            }

            if ($level & Territory::DISTRITO) {
                $query->orWhereRaw('LENGTH(a.id) = 2');
            }
        });

        if (!is('admin') && $headquarters) {
            $territories->whereIn('headquarter_id', $headquarters);
        }

        $data = Territory::hydrate($territories)->get()->toArray();

        return $data;
    }

    public function actingTerritoryFilter($level = Territory::ALL)
    {
        $data = [];

        foreach ($this->actingTerritorySearch($level) as $elem) {
            $data[$elem->id] = $elem->name;
        }

        return $data;
    }

    public function actingTerritoryList($level = Territory::ALL)
    {
        return $this->actingTerritoryFilter($level);
    }

    /*
    |--------------------------------------------------------------------------
    | Range Territories
    |--------------------------------------------------------------------------
    */

    public function rangeTerritorySearch($level = Territory::ALL)
    {
        $search_term = $this->getSearchParam();
        $headquarters = restrictToHeadquarters();

        $territories = DB::table('headquarters_territories_range')
            ->join('territories as a', function ($query) use ($search_term, $level) {
                $query->on('a.id', 'LIKE', DB::raw('CONCAT(headquarters_territories_range.territory_id, "%")'))
                    ->orOn('a.id', '=', DB::raw('LEFT(headquarters_territories_range.territory_id, 2)'))
                    ->orOn('a.id', '=', DB::raw('LEFT(headquarters_territories_range.territory_id, 4)'));

                if ($search_term) {
                    $query->where('name', 'LIKE', "%$search_term%");
                }
            })
            ->leftJoin('territories as b', 'a.parent_id', '=', 'b.id')
            ->leftJoin('territories as c', 'b.parent_id', '=', 'c.id')
            ->selectRaw('a.id, CONCAT(a.name, IF(b.name is null, "", CONCAT(", ", b.name)), IF(c.name is null, "", CONCAT(", ", c.name))) as name, a.parent_id')
            ->groupBy('a.id')
            ->orderByRaw('LENGTH(a.id), a.id');

        $territories = $territories->where(function ($query) use ($level) {
            if ($level & Territory::FREGUESIA) {
                $query->whereRaw('LENGTH(a.id) = 6');
            }

            if ($level & Territory::CONCELHO) {
                $query->orWhereRaw('LENGTH(a.id) = 4');
            }

            if ($level & Territory::DISTRITO) {
                $query->orWhereRaw('LENGTH(a.id) = 2');
            }
        });

        if (!is('admin') && $headquarters) {
            $territories->whereIn('headquarter_id', $headquarters);
        }

        $data = Territory::hydrate($territories->get()->toArray());

        return $data;
    }

    public function rangeTerritoryFilter($level = Territory::ALL)
    {
        $data = [];

        foreach ($this->rangeTerritorySearch($level) as $elem) {
            $data[$elem->id] = $elem->name;
        }

        return $data;
    }

    public function rangeTerritoryList($level = Territory::ALL)
    {
        return $this->rangeTerritoryFilter($level);
    }
}
