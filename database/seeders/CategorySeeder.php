<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'description' => 'Phones, laptops, and everyday gadgets.',
            ],
            [
                'name' => 'Fashion',
                'description' => 'Clothing and accessories for men and women.',
            ],
            [
                'name' => 'Home & Living',
                'description' => 'Furniture and essentials for modern homes.',
            ],
        ];

        foreach ($categories as $category) {
            Category::query()->updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                ],
            );
        }
    }
}
