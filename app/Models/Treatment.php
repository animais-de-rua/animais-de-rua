<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Treatment extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'treatments';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['treatment_type_id', 'vet_id', 'expense', 'date'];
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

    public function vet()
    {
        return $this->belongsTo('App\Models\Vet', 'vet_id');
    }

    public function treatment_type()
    {
        return $this->belongsTo('App\Models\TreatmentType', 'treatment_type_id');
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

    public function getVetLinkAttribute() {
        return "<a href='/admin/vet/{$this->vet->id}/edit'>".str_limit($this->vet->name, 60, "...")."</a>";
    }

    public function getFullExpenseAttribute() {
        return $this->expense . "€";
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
