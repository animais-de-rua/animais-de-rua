<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
    protected $fillable = ['name', 'alias', 'email', 'phone', 'territory_id'];
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

    public function getTotalDonatedValue() {
        $donations = data_get_first($this, 'donations', 'total', 0);

        return $donations != 0 ? $donations . "€" : '-';
    }

    public function getDetailAttribute() {
        return "{$this->name} ({$this->email})";
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
   
    public function toArray() {
        $data = parent::toArray();

        $data['detail'] = $this->detail;

        return $data;
    }
}
