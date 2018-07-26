<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Donation extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'donations';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['process_id', 'godfather_id', 'value', 'status', 'date'];
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

    public function getProcessLinkAttribute() {
        return "<a href='/admin/process/{$this->process->id}/edit'>".str_limit($this->process->name, 60, "...")."</a>";
    }

    public function getGodfatherLinkAttribute() {
        return "<a href='/admin/godfather/{$this->godfather->id}/edit'>".str_limit($this->godfather->name, 60, "...")."</a>";
    }

    public function getFullValueAttribute() {
        return $this->value . "€";
    }

    public function getFullStatusAttribute() {
        $label = ucfirst(__($this->status));

        if($this->status == "unconfirmed")
            $label = "<b style='color:#A00'>$label</b>";

        return $label;
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
