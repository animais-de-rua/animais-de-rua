<?php

namespace App\Models;

use App\Models\Traits\HasTranslations;
use Backpack\CRUD\CrudTrait;

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
        $expenses = data_get_first($this, 'treatments', 'total_expenses', 0);

        return $expenses != 0 ? $expenses . '€' : '-';
    }

    public function getTotalOperationsValue()
    {
        $operations = data_get_first($this, 'treatments', 'total_operations', 0);

        return $operations != 0 ? $operations : '-';
    }

    public function getOperationsAverageValue()
    {
        $expenses = data_get_first($this, 'treatments', 'total_expenses', 0);
        $operations = data_get_first($this, 'treatments', 'total_operations', 0);

        return $operations > 0 ? number_format($expenses / $operations, 2) . '€' : '-';
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
