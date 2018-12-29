<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;

class Godfather extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'godfathers';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['name', 'email', 'phone', 'headquarter_id', 'territory_id', 'user_id'];
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

    public function donations()
    {
        return $this->hasMany('App\Models\Donation', 'godfather_id');
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

    public function getTotalDonatedValue()
    {
        $donations = data_get_first($this, 'donations', 'total', 0);
        return $donations != 0 ? $donations . '€' : '-';
    }

    public function getDetailAttribute()
    {
        return "{$this->name} ({$this->email})";
    }

    public function getTotalDonatedStats()
    {
        $donations = $this->donations->reduce(function ($carry, $item) {return $carry + $item->value;});
        return $donations != 0 ? $donations . '€' : '-';
    }

    public function getTotalDonationsStats()
    {
        return count($this->donations);
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
