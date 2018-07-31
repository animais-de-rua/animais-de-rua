<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Backpack\CRUD\ModelTraits\SpatieTranslatable\HasTranslations;

class TreatmentType extends Model
{
    use CrudTrait;
    use HasTranslations;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'treatment_types';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['name', 'operation_time'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $translatable = ['name'];

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

    public function treatments()
    {
        return $this->hasMany('App\Models\Treatment', 'treatment_type_id');
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

    public function getTotalExpensesValue() {
        $value = data_get($this, 'treatments');
        return (sizeof($value) ? $value[0]->total_expenses : 0) . "€";
    }

    public function getTotalOperationsValue() {
        $value = data_get($this, 'treatments');
        return (sizeof($value) ? $value[0]->total_operations : 0);
    }

    public function getOperationsAverageValue() {
        $value = data_get($this, 'treatments');
        $average = $value[0]->total_expenses / $value[0]->total_operations;

        return $average > 0 ? number_format($average, 2) . "€" : '-';
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
