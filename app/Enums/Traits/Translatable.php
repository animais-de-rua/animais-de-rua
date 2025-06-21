<?php

namespace App\Enums\Traits;

use BackedEnum;

trait Translatable
{
    public function transValue(): string
    {
        return trans($this->value);
    }

    public static function transValues(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (BackedEnum $case): array => [$case->value => ucfirst($case->transValue())])
            ->toArray();
    }
}
