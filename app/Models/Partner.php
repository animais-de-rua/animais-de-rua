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
    protected $fillable = ['name', 'description', 'email', 'phone1', 'phone1_info', 'phone2', 'phone2_info', 'url', 'facebook', 'address', 'address_info', 'latlong', 'benefit', 'notes', 'status', 'user_id', 'image'];
    // protected $hidden = [];
    // protected $dates = [];
    protected $translatable = ['benefit'];

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

    public function getUserLinkAttribute()
    {
        return $this->getLink($this->user);
    }

    public function getCategoryListAttribute()
    {
        return join(', ', $this->categories()->pluck('name')->toArray());
    }

    public function getTerritoryListAttribute()
    {
        return join(', ', $this->territories()->pluck('name')->toArray());
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setImageAttribute($value)
    {
        $attribute_name = 'image';
        $disk = 'uploads';

        // if the image was erased
        if ($value == null) {
            \Storage::disk($disk)->delete($this->{$attribute_name});
            $this->attributes[$attribute_name] = null;
        }

        // if a base64 was sent, store it in the db
        if (starts_with($value, 'data:image')) {
            $filename = str_slug($this->attributes['name']) . '.jpg';

            $image = \Image::make($value);
            if ($image->width() > 192) {
                $image->resize(192, null, function ($c) {$c->aspectRatio();});
            }

            \Storage::disk($disk)->put('partners/' . $filename, $image->stream('jpg', 82));
            $this->attributes[$attribute_name] = 'partners/' . $filename;
        }
    }
}
