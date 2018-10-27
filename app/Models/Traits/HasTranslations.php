<?php

namespace App\Models\Traits;

trait HasTranslations
{
    use \Backpack\CRUD\ModelTraits\SpatieTranslatable\HasTranslations;

    /**
     * This functions fixes the problem introduced in spatie-translatable v3
     * getTranslation is set to return only string, and that causes translatable arrays/fake fields to fail
     */
    public function getTranslation(string $key, string $locale, bool $useFallbackLocale = true)
    {
        $locale = $this->normalizeLocale($key, $locale, $useFallbackLocale);

        $translations = $this->getTranslations($key);

        $translation = $translations[$locale] ?? '';

        if ($this->hasGetMutator($key)) {
            return $this->mutateAttribute($key, $translation);
        }

        return $translation;
    }
}
