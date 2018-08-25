<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;

class Donation extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'donations';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['process_id', 'godfather_id', 'value', 'status', 'date'];
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

    public function godfather()
    {
        return $this->belongsTo('App\Models\Godfather', 'godfather_id');
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

    public function getGodfatherLinkAttribute() {
        return $this->getLink($this->godfather);
    }

    public function getFullValueAttribute() {
        return $this->value . "€";
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
