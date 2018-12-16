<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;

class Fat extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'fats';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['name', 'alias', 'email', 'phone', 'headquarter_id', 'territory_id', 'user_id'];
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

    public function adoptions()
    {
        return $this->hasMany('App\Models\Adoption', 'fat_id');
    }

    public function territory()
    {
        return $this->belongsTo('App\Models\Territory', 'territory_id');
    }

    public function headquarter()
    {
        return $this->belongsTo('App\Models\Headquarter', 'headquarter_id');
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

    public function getDetailAttribute()
    {
        return "{$this->name} ({$this->email})";
    }

    public function getUserLinkAttribute()
    {
        return $this->getLink($this->user, is('admin'));
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function toArray()
    {
        $data = parent::toArray();

        $data['detail'] = $this->detail;

        return $data;
    }
}
