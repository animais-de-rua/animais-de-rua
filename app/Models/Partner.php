<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Backpack\CRUD\ModelTraits\SpatieTranslatable\HasTranslations;

class Partner extends Model
{
    use CrudTrait;
    use HasTranslations;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'partners';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['name', 'description', 'email', 'phone', 'url', 'address', 'latlong', 'benefit', 'notes', 'status', 'user_id'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $translatable = ['name', 'description', 'benefit'];

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

    public function territories()
    {
        return $this->belongsToMany('App\Models\Territory', 'partners_territories', 'partner_id', 'territory_id');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\PartnerCategory', 'partners_categories', 'partner_id', 'partner_category_list_id');
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

    public function getUserLinkAttribute() {
        return $this->getLink($this->user);
    }

    public function getCategoryListAttribute() {
        return join(', ', $this->categories()->pluck('name')->toArray());
    }

    public function getTerritoryListAttribute() {
        return join(', ', $this->territories()->pluck('name')->toArray());
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
