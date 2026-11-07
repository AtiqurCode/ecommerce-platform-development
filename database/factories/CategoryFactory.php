<?php

namespace Database\Factories;

use App\Models\Category;
use App\Support\EcommerceCatalog;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = fake()->randomElement(EcommerceCatalog::categories());
        $name = $category['name'].' '.fake()->unique()->numerify('###');

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $category['description'],
        ];
    }
}
