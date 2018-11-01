<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;

class Territory extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'territories';
    protected $primaryKey = 'id';
    public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];
    protected $casts = [
        'id' => 'string',
    ];

    const DISTRITO = 1;
    const CONCELHO = 2;
    const FREGUESIA = 4;
    const ALL = 7;

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

    public function children()
    {
        return $this->hasMany('App\Models\Territory', 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Territory', 'parent_id');
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

    public function getFullnameAttribute()
    {
        return $this->name . ($this->parent()->exists() ? ', ' . $this->parent()->first()->fullname : '');
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
