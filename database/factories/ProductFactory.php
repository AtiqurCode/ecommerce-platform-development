<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Subcategory;
use App\Support\EcommerceCatalog;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product = fake()->randomElement(EcommerceCatalog::products());
        $suffix = fake()->unique()->bothify('??##');
        $name = $product['name'].' '.$suffix;

        return [
            'subcategory_id' => Subcategory::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $product['description'],
            'image' => 'products/placeholder.jpg',
            'old_price' => $product['old_price'],
            'new_price' => $product['new_price'],
        ];
    }
}
