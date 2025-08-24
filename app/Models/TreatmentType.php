<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;

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

    public function getTotalExpensesValue()
    {
        $expenses = $this->total_expenses ?: data_get_first($this, 'treatments', 'total_expenses', 0);

        return $expenses != 0 ? $expenses . '€' : '-';
    }

    public function getTotalOperationsValue()
    {
        $operations = $this->total_operations ?: data_get_first($this, 'treatments', 'total_operations', 0);

        return $operations != 0 ? $operations : '-';
    }

    public function getOperationsAverageValue()
    {
        $average = $this->average ?: data_get_first($this, 'treatments', 'average', 0);

        return $average > 0 ? number_format($average, 2) . '€' : '-';
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setOperationTimeAttribute($value)
    {
        $parts = explode(':', $value);
        if (count($parts) >= 2) {
            $this->attributes['operation_time'] = $parts[0] * 60 + $parts[1];
        }

    }

    public function getOperationTimeAttribute($value)
    {
        return sprintf('%02d:%02d', floor($value / 60), $value % 60);
    }
}
