<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Process extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'processes';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['name', 'contact', 'phone', 'email', 'address', 'territory_id', 'headquarter_id', 'specie', 'amount_males', 'amount_females', 'amount_other', 'status', 'history', 'notes', 'latlong', 'images'];
    protected $casts = ['images' => 'array'];
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

    public function headquarter()
    {
        return $this->belongsTo('App\Models\Headquarter', 'headquarter_id');
    }

    public function territory()
    {
        return $this->belongsTo('App\Models\Territory', 'territory_id');
    }

    public function donations()
    {
        return $this->hasMany('App\Models\Donation', 'process_id');
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

    public function getLinkAttribute()
    {
        return "<a href='".url('/admin/process/'.$this->id.'/edit')."'>".$this->name."</a>";
    }

    public function getDateAttribute()
    {
        return $this->created_at ? explode(' ', $this->created_at)[0] : '';
    }

    public function getDetailAttribute() {
        $hq = $this->headquarter;
        return "{$this->name} (" . (isset($hq) ? $hq['name'].', ' : '') ."{$this->date})";
    }

    public function getTotalDonatedValue() {
        $value = data_get($this, 'donations');
        return (sizeof($value) ? $value[0]->total : 0) . "€";
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