<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class TreatmentType extends Model
{
    use CrudTrait;
    use HasTranslations;

    protected $fillable = [
        'name',
        'operation_time',
    ];
    protected $translatable = [
        'name',
    ];

    /**
     * @return HasMany<Treatment, $this>
     */
    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class, 'treatment_type_id');
    }

    // @deprecated
    public function getTotalExpensesValue(): string
    {
        $expenses = $this->total_expenses ?: data_get_first($this, 'treatments', 'total_expenses', 0);

        return $expenses != 0 ? $expenses.'€' : '-';
    }

    // @deprecated
    public function getTotalOperationsValue(): string
    {
        $operations = $this->total_operations ?: data_get_first($this, 'treatments', 'total_operations', 0);

        return $operations != 0 ? $operations : '-';
    }

    // @deprecated
    public function getOperationsAverageValue(): string
    {
        $average = $this->average ?: data_get_first($this, 'treatments', 'average', 0);

        return $average > 0 ? number_format($average, 2).'€' : '-';
    }

    // @deprecated
    public function setOperationTimeAttribute($value): void
    {
        $parts = explode(':', $value);
        if (count($parts) >= 2) {
            $this->attributes['operation_time'] = ((int) $parts[0]) * 60 + ((int) $parts[1]);
        }
    }

    // @deprecated
    public function getOperationTimeAttribute($value): string
    {
        return sprintf('%02d:%02d', floor($value / 60), $value % 60);
    }
}
