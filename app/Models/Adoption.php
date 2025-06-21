<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Database\Factories\AdoptionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Adoption extends Model
{
    use CrudTrait;
    /** @use HasFactory<AdoptionFactory */
    use HasFactory;

    protected $fillable = [
        'name',
        'name_after',
        'age',
        'gender',
        'microchip',
        'sterilized',
        'vaccinated',
        'processed',
        'individual',
        'docile',
        'abandoned',
        'history',
        'images',
        'status',
        'adoption_date',
        'foal',
    ];
    protected $casts = [
        'images' => 'array',
        'age' => 'array',
    ];

    /**
     * @return BelongsTo<Process, $this>
     */
    public function process(): BelongsTo
    {
        return $this->belongsTo(Process::class, 'process_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo<Fat, $this>
     */
    public function fat(): BelongsTo
    {
        return $this->belongsTo(Fat::class, 'fat_id');
    }

    /**
     * @return BelongsTo<Adopter, $this>
     */
    public function adopter(): BelongsTo
    {
        return $this->belongsTo(Adopter::class, 'adopter_id');
    }

    // @deprecated
    public function addAdopter(): string
    {
        $disabled = $this->status != 'open';

        return '
        <a class="btn btn-xs btn-'.($disabled ? 'default' : 'primary').' '.($disabled ? 'disabled' : '').'" href="/admin/adoption/'.$this->id.'/edit" title="'.__('Add adopter').'">
        <i class="fa fa-plus"></i> '.ucfirst(__('adopter')).'
        </a>';
    }

    // @deprecated
    public function getNameLinkAttribute()
    {
        return $this->getLink($this, true, '');
    }

    // @deprecated
    public function getProcessLinkAttribute()
    {
        return $this->getLink($this->process, true, '');
    }

    // @deprecated
    public function getAdopterLinkAttribute()
    {
        return $this->getLink($this->adopter, true, 'edit');
    }

    // @deprecated
    public function getHeadquarterAttribute(): string
    {
        return $this->process->headquarter->name;
    }

    // @deprecated
    public function getUserLinkAttribute()
    {
        return $this->getLink($this->user, is('admin'));
    }

    // @deprecated
    public function getFatLinkAttribute()
    {
        return $this->getLink($this->fat, is('admin'));
    }

    // @deprecated
    public function getGenderValueAttribute(): string
    {
        return ucfirst(__($this->gender));
    }

    // @deprecated
    public function getSterilizedValueAttribute(): string
    {
        return ucfirst(__($this->sterilized ? 'yes' : 'no'));
    }

    // @deprecated
    public function getVaccinatedValueAttribute(): string
    {
        return ucfirst(__($this->vaccinated ? 'yes' : 'no'));
    }

    // @deprecated
    public function getDetailAttribute(): string
    {
        $process = $this->process ? ' ('.$this->process->name.')' : '';

        return "$this->id - $this->name$process";
    }

    // @deprecated
    public function setAgeAttribute($value): void
    {
        $this->attributes['age'] = $value[0] * 12 + $value[1];
    }

    // @deprecated
    public function getAgeAttribute($value): array
    {
        return [floor($value / 12), $value % 12];
    }

    // @deprecated
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

    // @deprecated
    public function toArray(): array
    {
        $data = parent::toArray();

        $data['detail'] = $this->detail;
        $data['thumb'] = $this->images ? thumb_image($this->images[0]) : null;

        return $data;
    }
}
