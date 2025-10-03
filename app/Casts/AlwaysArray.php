<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class AlwaysArray implements CastsAttributes
{
    /**
     * Transform the attribute from the underlying model values.
     */
    public function get($model, string $key, $value, array $attributes): array
    {
        $decoded = $value ? json_decode($value, true) : [];
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Transform the attribute to its underlying storage value.
     */
    public function set($model, string $key, $value, array $attributes): string
    {
        return json_encode($value ?? []);
    }
}
