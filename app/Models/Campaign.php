<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Spatie\Translatable\HasTranslations;

class Campaign extends Model
{
    use CrudTrait;
    use HasTranslations;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'campaigns';
    protected $primaryKey = 'id';

    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['name', 'introduction', 'description', 'image'];

    // protected $hidden = [];
    // protected $dates = [];
    protected array $translatable = ['name', 'introduction', 'description'];

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

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setImageAttribute($value)
    {
        $filename = json_decode($this->attributes['name'])->pt;
        $this->saveImage($this, $value, 'campaigns/', $filename, 800, 88);
    }
}
