<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Database\Factories\DonationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Donation extends Model
{
    use CrudTrait;
    /** @use HasFactory<DonationFactory> */
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'donations';
    protected $primaryKey = 'id';

    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['process_id', 'type', 'godfather_id', 'headquarter_id', 'protocol_id', 'value', 'status', 'date', 'user_id', 'notes'];
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

    public function headquarter()
    {
        return $this->belongsTo('App\Models\Headquarter', 'headquarter_id');
    }

    public function protocol()
    {
        return $this->belongsTo('App\Models\Protocol', 'protocol_id');
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

    public function getProcessLinkAttribute()
    {
        return $this->getLink($this->process, true, '');
    }

    public function getGodfatherLinkAttribute()
    {
        return $this->getLink($this->godfather, is('admin', 'accountancy'));
    }

    public function getHeadquarterLinkAttribute()
    {
        return $this->getLink($this->headquarter, is('admin', 'accountancy'));
    }

    public function getProtocolLinkAttribute()
    {
        if (! $this->protocol || ! $this->protocol->headquarter) {
            return '-';
        }

        $name = "{$this->protocol->name} ({$this->protocol->headquarter->name})";

        return is('admin', 'accountancy') ? "<a href='/admin/protocol/{$this->protocol->id}/edit'>$name</a>" : $name;
    }

    public function getFullValueAttribute()
    {
        return $this->value.'€';
    }

    public function getUserLinkAttribute()
    {
        return $this->getLink($this->user);
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
