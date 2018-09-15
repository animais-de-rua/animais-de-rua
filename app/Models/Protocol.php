<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;

class Protocol extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'protocols';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['council', 'name', 'email', 'phone', 'address', 'description', 'territory_id', 'user_id'];
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

    public function territory()
    {
        return $this->belongsTo('App\Models\Territory', 'territory_id');
    }

    public function process()
    {
        return $this->belongsTo('App\Models\Process', 'process_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
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

    public function getUserLinkAttribute()
    {
        return $this->getLink($this->user);
    }

    public function getProcessLinkAttribute()
    {
        return $this->getLink($this->process);
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
