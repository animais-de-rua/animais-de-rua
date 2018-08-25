<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;

class Appointment extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'appointments';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['process_id', 'user_id', 'vet_id_1', 'date_1', 'vet_id_2', 'date_2', 'amount_males', 'amount_females', 'notes', 'status'];
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

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function vet1()
    {
        return $this->belongsTo('App\Models\Vet', 'vet_id_1');
    }

    public function vet2()
    {
        return $this->belongsTo('App\Models\Vet', 'vet_id_2');
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

    public function getUserLinkAttribute() {
        return $this->getLink($this->user);
    }

    public function getVet1LinkAttribute() {
        return $this->getLink($this->vet1);
    }

    public function getVet2LinkAttribute() {
        return $this->getLink($this->vet2);
    }

    public function getAnimalsValue() {
        return "{$this->amount_males} / {$this->amount_females}";
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
