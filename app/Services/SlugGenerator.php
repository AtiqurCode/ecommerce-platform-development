<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SlugGenerator
{
    /**
     * Generate a unique slug for the given model and name.
     *
     * @param  class-string<Model>  $model
     * @param  array<string, mixed>  $extraConstraints
     */
    public function unique(
        string $model,
        string $name,
        ?int $ignoreId = null,
        array $extraConstraints = [],
        string $column = 'slug',
    ): string {
        $base = Str::slug($name);
        $slug = $base !== '' ? $base : 'item';
        $counter = 1;

        while (
            $model::query()
                ->where($column, $slug)
                ->when($ignoreId !== null, fn ($query) => $query->whereKeyNot($ignoreId))
                ->when($extraConstraints !== [], fn ($query) => $query->where($extraConstraints))
                ->exists()
        ) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
