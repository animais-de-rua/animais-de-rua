<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Database\Factories\VetFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vet extends Model
{
    use CrudTrait;
    /** @use HasFactory<VetFactory> */
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'vets';
    protected $primaryKey = 'id';

    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['name', 'email', 'phone', 'url', 'address', 'latlong', 'headquarter_id', 'status'];
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
     * @return BelongsToMany<Headquarter, Vet>
     * */
    public function headquarters(): BelongsToMany
    {
        return $this->belongsToMany(Headquarter::class, 'vets_headquarters', 'vet_id', 'headquarter_id');
    }

    /**
     * @return HasMany<Treatment, Vet>
     * */
    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class, 'vet_id');
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

    public function getTotalExpensesValue(): string
    {
        $expenses = data_get_first($this, 'treatments', 'total_expenses', 0);

        return $expenses != 0 ? $expenses.'€' : '-';
    }

    public function getTotalOperationsValue(): int
    {
        return data_get_first($this, 'treatments', 'total_operations', 0);
    }

    public function getTotalExpensesStats(): string
    {
        $expenses = $this->treatments->reduce(function ($carry, $item) {
            return $carry + $item->expense;
        });

        return $expenses != 0 ? $expenses.'€' : '-';
    }

    public function getTotalOperationsStats(): int
    {
        return $this->treatments->reduce(function ($carry, $item) {
            return $carry + $item->affected_animals;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
