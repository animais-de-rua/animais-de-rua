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
    protected $fillable = ['process_id', 'user_id', 'fat_id', 'name', 'age', 'gender', 'sterilized', 'vaccinated', 'processed', 'history', 'images'];
    protected $casts = ['images' => 'array', 'age' => 'array'];
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
        return $this->getLink($this->process, '');
    }

    public function getUserLinkAttribute()
    {
        return $this->getLink($this->user, is('admin'));
    }

    public function getFatLinkAttribute()
    {
        return $this->getLink($this->fat, is('admin'));
    }

    public function getGenderValueAttribute()
    {
        return ucfirst(__($this->gender));
    }

    public function getSterilizedValueAttribute()
    {
        return ucfirst(__($this->sterilized ? 'yes' : 'no'));
    }

    public function getVaccinatedValueAttribute()
    {
        return ucfirst(__($this->vaccinated ? 'yes' : 'no'));
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setAgeAttribute($value)
    {
        $this->attributes['age'] = $value[0] * 12 + $value[1];
    }

    public function getAgeAttribute($value)
    {
        return [floor($value / 12), $value % 12];
    }

    public function getAgeValueAttribute()
    {
        list($y, $m) = $this->age;

        $result = [];
        if ($y > 0) {
            $result[] = "$y " . ($y > 1 ? __('years') : __('year'));
        }

        if ($m > 0) {
            $result[] = "$m " . ($m > 1 ? __('months') : __('month'));
        }

        return join(' ' . __('and') . ' ', $result);
    }
}
