<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Database\Factories\GodfatherFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Godfather extends Model
{
    use CrudTrait;
    /** @use HasFactory<GodfatherFactory> */
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'godfathers';
    protected $primaryKey = 'id';

    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['name', 'email', 'phone', 'notes', 'territory_id', 'user_id'];
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

    public function headquarters()
    {
        return $this->belongsToMany('App\Models\Headquarter', 'godfathers_headquarters', 'godfather_id', 'headquarter_id');
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

        return $donations != 0 ? $donations.'€' : '-';
    }

    public function getDetailAttribute()
    {
        return "{$this->name} ({$this->email})";
    }

    public function getTotalDonatedStats()
    {
        $donations = $this->donations->reduce(function ($carry, $item) {
            return $carry + $item->value;
        });

        return $donations != 0 ? $donations.'€' : '-';
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
