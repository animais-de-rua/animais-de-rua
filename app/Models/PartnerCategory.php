<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\Translatable\HasTranslations;

class PartnerCategory extends Model
{
    use CrudTrait;
    use HasTranslations;

    protected $table = 'partner_category_list';
    protected $fillable = [
        'name',
        'description',
    ];
    protected $hidden = [
        'pivot',
    ];
    protected $translatable = [
        'name',
        'description',
    ];

    /**
     * @return HasManyThrough<Partner, $this>
     */
    public function partners(): HasManyThrough
    {
        return $this->hasManyThrough(Partner::class, 'partners_categories', 'partner_category_list_id');
    }
}
