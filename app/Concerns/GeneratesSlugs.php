<?php

namespace App\Concerns;

use App\Services\SlugGenerator;
use Illuminate\Database\Eloquent\Model;

/**
 * @deprecated Prefer injecting App\Services\SlugGenerator.
 */
trait GeneratesSlugs
{
    /**
     * Generate a unique slug for the given model and name.
     *
     * @param  class-string<Model>  $model
     * @param  array<string, mixed>  $extraConstraints
     */
    protected function uniqueSlug(
        string $model,
        string $name,
        ?int $ignoreId = null,
        array $extraConstraints = [],
        string $column = 'slug',
    ): string {
        return app(SlugGenerator::class)->unique(
            $model,
            $name,
            $ignoreId,
            $extraConstraints,
            $column,
        );
    }
}
