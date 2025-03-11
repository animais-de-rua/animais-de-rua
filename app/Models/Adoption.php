<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Database\Factories\AdoptionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Process process
 * @property User user
 * @property Fat fat
 * @property Adopter adopter
 * @property string status
 * @property int id
 * @property string $gender
 * @property bool $sterilized
 * @property bool $vaccinated
 * @property string $name
 * @property array $age
 * @property string $detail
 * @property array $images
 */
class Adoption extends Model
{
    use CrudTrait;
    /** @use HasFactory<AdoptionFactory */
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'adoptions';
    protected $primaryKey = 'id';

    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = ['process_id', 'user_id', 'fat_id', 'name', 'name_after', 'age', 'gender', 'microchip', 'sterilized', 'vaccinated', 'processed', 'individual', 'docile', 'abandoned', 'history', 'images', 'status', 'adoption_date', 'foal'];
    protected $casts = ['images' => 'array', 'age' => 'array'];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function addAdopter(): string
    {
        $disabled = $this->status != 'open';

        return '
        <a class="btn btn-xs btn-'.($disabled ? 'default' : 'primary').' '.($disabled ? 'disabled' : '').'" href="/admin/adoption/'.$this->id.'/edit" title="'.__('Add adopter').'">
        <i class="fa fa-plus"></i> '.ucfirst(__('adopter')).'
        </a>';
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * @return BelongsTo<Process>
     */
    public function process(): BelongsTo
    {
        return $this->belongsTo(Process::class, 'process_id');
    }

    /**
     * @return BelongsTo<User>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo<Fat>
     */
    public function fat(): BelongsTo
    {
        return $this->belongsTo(Fat::class, 'fat_id');
    }

    /**
     * @return BelongsTo<Adopter>
     */
    public function adopter(): BelongsTo
    {
        return $this->belongsTo(Adopter::class, 'adopter_id');
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
    public function getNameLinkAttribute()
    {
        return $this->getLink($this, true, '');
    }

    public function getProcessLinkAttribute()
    {
        return $this->getLink($this->process, true, '');
    }

    public function getAdopterLinkAttribute()
    {
        return $this->getLink($this->adopter, true, 'edit');
    }

    public function getHeadquarterAttribute(): string
    {
        return $this->process->headquarter->name;
    }

    public function getUserLinkAttribute()
    {
        return $this->getLink($this->user, is('admin'));
    }

    public function getFatLinkAttribute()
    {
        return $this->getLink($this->fat, is('admin'));
    }

    public function getGenderValueAttribute(): string
    {
        return ucfirst(__($this->gender));
    }

    public function getSterilizedValueAttribute(): string
    {
        return ucfirst(__($this->sterilized ? 'yes' : 'no'));
    }

    public function getVaccinatedValueAttribute(): string
    {
        return ucfirst(__($this->vaccinated ? 'yes' : 'no'));
    }

    public function getDetailAttribute(): string
    {
        $process = $this->process ? ' ('.$this->process->name.')' : '';

        return "$this->id - $this->name$process";
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setAgeAttribute($value): void
    {
        $this->attributes['age'] = $value[0] * 12 + $value[1];
    }

    public function getAgeAttribute($value): array
    {
        return [floor($value / 12), $value % 12];
    }

    public function getAgeValueAttribute(): string
    {
        [$y, $m] = $this->age;

        $result = [];
        if ($y > 0) {
            $result[] = "$y ".($y > 1 ? __('years') : __('year'));
        }

        if ($m > 0) {
            $result[] = "$m ".($m > 1 ? __('months') : __('month'));
        }

        return implode(' '.__('and').' ', $result);
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        $data['detail'] = $this->detail;
        $data['thumb'] = $this->images ? thumb_image($this->images[0]) : null;

        return $data;
    }
}
