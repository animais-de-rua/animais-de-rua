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
    protected $fillable = ['appointment_id', 'treatment_type_id', 'vet_id', 'affected_animals', 'affected_animals_new', 'user_id', 'expense', 'date', 'status'];
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

    public function appointment()
    {
        return $this->belongsTo('App\Models\Appointment', 'appointment_id');
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

    public function getProcessLinkAttribute()
    {
        return $this->getLink($this->appointment ? $this->appointment->process : null, true, '');
    }

    public function getVetLinkAttribute()
    {
        return $this->getLink($this->vet, is('admin', 'vet'));
    }

    public function getUserLinkAttribute()
    {
        return $this->getLink($this->user, is('admin'));
    }

    public function getFullExpenseAttribute()
    {
        return $this->expense . '€';
    }

    public function getAffectedAnimalsNew($appointment_id)
    {
        return Treatment::selectRaw('SUM(affected_animals_new) as total')->where('appointment_id', $appointment_id)->first()->total;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
