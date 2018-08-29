<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;

class Adoption extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'adoptions';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['process_id', 'user_id', 'fat_id', 'name', 'history'];
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

    public function fat()
    {
        return $this->belongsTo('App\User', 'fat_id');
    }

    public function animal()
    {
        return $this->hasMany('App\Models\Animal', 'adoption_id');
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

    public function getFatLinkAttribute() {
        return $this->getLink($this->fat);
    }

    public function getAnimalsAttribute() {
        return count($this->animal);
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
