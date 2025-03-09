<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;

class Vet extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'vets';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['name', 'email', 'phone', 'url', 'address', 'latlong', 'headquarter_id', 'status'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function headquarters()
    {
        return $this->belongsToMany('App\Models\Headquarter', 'vets_headquarters', 'vet_id', 'headquarter_id');
    }

    public function treatments()
    {
        return $this->hasMany('App\Models\Treatment', 'vet_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    public function getTotalExpensesValue()
    {
        $expenses = data_get_first($this, 'treatments', 'total_expenses', 0);
        return $expenses != 0 ? $expenses . '€' : '-';
    }

    public function getTotalOperationsValue()
    {
        $operations = data_get_first($this, 'treatments', 'total_operations', 0);
        return $operations;
    }

    public function getTotalExpensesStats()
    {
        $expenses = $this->treatments->reduce(function ($carry, $item) {return $carry + $item->expense;});
        return $expenses != 0 ? $expenses . '€' : '-';
    }

    public function getTotalOperationsStats()
    {
        return $this->treatments->reduce(function ($carry, $item) {return $carry + $item->affected_animals;});
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
