<?php

namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use App\Models\Godfather;
use App\Models\Headquarter;
use App\Models\Process;
use App\Models\Territory;
use App\Models\Treatment;
use App\Models\TreatmentType;
use App\Models\Vet;
use App\User;

class APICrudController extends CrudController
{
    public function getSearchParam(Request $request)
    {
        return $request ? ($request->has('q') || $request->has('term') ? $request->input('q') . $request->input('term') : false) : false;
    }

    /*
    |--------------------------------------------------------------------------
    | Default Search
    |--------------------------------------------------------------------------
    */
    public function entitySearch($entity, $searchFields, Request $request)
    {
        $search_term = $this->getSearchParam($request);

        if ($search_term && count($searchFields)) {
            $results = $entity::where(array_shift($searchFields), 'LIKE', "%$search_term%");

            foreach ($searchFields as $field) {
                $results = $results->orWhere($field, 'LIKE', "%$search_term%");
            }
            $results = $results->paginate(10);
        } else {
            $results = $entity::paginate(10);
        }

        return $results;
    }

    /*
    |--------------------------------------------------------------------------
    | Godfather
    |--------------------------------------------------------------------------
    */
    public function godfatherSearch(Request $request)
    {
        return $this->entitySearch(Godfather::class, ['name', 'email'], $request);
    }

    public function godfatherFilter(Request $request)
    {
        return $this->godfatherSearch($request)->pluck('name', 'id');
    }

    /*
    |--------------------------------------------------------------------------
    | Headquarter
    |--------------------------------------------------------------------------
    */
    public function headquarterSearch(Request $request)
    {
        return $this->entitySearch(Headquarter::class, ['name'], $request);
    }

    public function headquarterFilter(Request $request)
    {
        return $this->headquarterSearch($request)->pluck('name', 'id');
    }

    public function headquarterList()
    {
        return $this->headquarterFilter(new Request())->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | Process
    |--------------------------------------------------------------------------
    */
    public function processSearch(Request $request)
    {
        return $this->entitySearch(Process::class, ['name'], $request);
    }

    public function processFilter(Request $request)
    {
        return $this->processSearch($request)->pluck('name', 'id');
    }

    /*
    |--------------------------------------------------------------------------
    | User
    |--------------------------------------------------------------------------
    */
    public function userSearch(Request $request)
    {
        return $this->entitySearch(User::class, ['name', 'email'], $request);
    }

    public function userFilter(Request $request)
    {
        return $this->userSearch($request)->pluck('name', 'id');
    }

    /*
    |--------------------------------------------------------------------------
    | Vet
    |--------------------------------------------------------------------------
    */
    public function vetSearch(Request $request)
    {
        return $this->entitySearch(Vet::class, ['name'], $request);
    }

    public function vetFilter(Request $request)
    {
        return $this->vetSearch($request)->pluck('name', 'id');
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
    public function territorySearch($level = Territory::ALL, Request $request)
    {
        $search_term = $this->getSearchParam($request);
        $data = [];

        if($level & Territory::DISTRITO) {
            $dataC = DB::table('territories as a')
                ->select(DB::raw("a.id, a.name"));

            $where = [['a.level', '=', 1]];
            if($search_term) {
                array_push($where, ['a.name', 'LIKE', "%$search_term%"]);
                $dataC = $dataC->limit(2);
            }

            foreach ($dataC->where($where)->get() as $v)
                $data[] = $v;
        }

        if($level & Territory::CONCELHO) {
            $dataB = DB::table('territories as a')
                ->join('territories as b', 'a.parent_id', '=' , 'b.id')
                ->select(DB::raw("a.id, CONCAT(a.name, ', ', b.name) name"));

            $where = [['a.level', '=', 2]];
            if($search_term) {
                array_push($where, ['a.name', 'LIKE', "%$search_term%"]);
                $dataB = $dataB->limit(4);
            }
            
            foreach ($dataB->where($where)->get() as $v)
                $data[] = $v;
        }

        if($level & Territory::FREGUESIA) {
            $dataA = DB::table('territories as a')
                ->join('territories as b', 'a.parent_id', '=' , 'b.id')
                ->join('territories as c', 'b.parent_id', '=' , 'c.id')
                ->select(DB::raw("a.id, CONCAT(a.name, ', ', b.name, ', ', c.name) name"));

            $where = [['a.level', '=', 3]];
            if($search_term) {
                array_push($where, ['a.name', 'LIKE', "%$search_term%"]);
                $dataA = $dataA->limit(8);
            }
            
            foreach ($dataA->where($where)->get() as $v)
                $data[] = $v;
        }

        $data = Territory::hydrate($data);

        return $data;
    }

    public function territoryFilter($level = Territory::ALL, Request $request)
    {
        $data = [];

        foreach ($this->territorySearch($level, $request) as $elem)
            $data[$elem->id] = $elem->name;

        return $data;
    }

    public function territoryList($level = Territory::ALL)
    {
        return $this->territoryFilter($level, new Request());
    }
}
