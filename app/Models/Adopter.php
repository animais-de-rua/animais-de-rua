<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Database\Factories\AdopterFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Adopter extends Model
{
    use CrudTrait;
    /** @use HasFactory<AdopterFactory> */
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'adopters';
    protected $primaryKey = 'id';

    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['name', 'email', 'phone', 'address', 'zip_code', 'adoption_date', 'id_card', 'territory_id', 'user_id'];
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

    /**
     * @return HasOne<Adoption>
     */
    public function adoption(): HasOne
    {
        return $this->hasOne(Adoption::class, 'adopter_id');
    }

    /**
     * @return BelongsTo<Territory>
     */
    public function territory(): BelongsTo
    {
        return $this->belongsTo(Territory::class, 'territory_id');
    }

    /**
     * @return BelongsTo<User>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*public function getAdoptionLinkAttribute()
    {
        return $this->getLink($this->adoption);
    }

    public function getUserLinkAttribute()
    {
        return $this->getLink($this->user, is('admin'));
    }+/

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
