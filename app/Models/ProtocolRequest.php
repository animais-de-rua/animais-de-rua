<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Database\Factories\ProtocolRequestFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProtocolRequest extends Model
{
    use CrudTrait;
    /** @use HasFactory<ProtocolRequestFactory> */
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'protocols_requests';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['council', 'name', 'email', 'phone', 'address', 'description', 'territory_id', 'user_id', 'protocol_id'];
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

    public function protocol()
    {
        return $this->belongsTo('App\Models\Protocol', 'protocol_id');
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
        return $this->getLink($this->user, is('admin'));
    }

    public function getProcessLinkAttribute()
    {
        return $this->getLink($this->process, true, '');
    }

    public function getProtocolLinkAttribute()
    {
        return $this->getLink($this->protocol, is('admin', 'protocols'));
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
