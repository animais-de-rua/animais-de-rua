<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;

class Treatment extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'treatments';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['process_id', 'treatment_type_id', 'vet_id', 'user_id', 'expense', 'date'];
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

    public function process()
    {
        return $this->belongsTo('App\Models\Process', 'process_id');
    }

    public function vet()
    {
        return $this->belongsTo('App\Models\Vet', 'vet_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function treatment_type()
    {
        return $this->belongsTo('App\Models\TreatmentType', 'treatment_type_id');
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

    public function getProcessLinkAttribute() {
        return $this->getLink($this->process);
    }

    public function getVetLinkAttribute() {
        return $this->getLink($this->vet);
    }

    public function getUserLinkAttribute() {
        return $this->getLink($this->user);
    }

    public function getFullExpenseAttribute() {
        return $this->expense . "€";
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
