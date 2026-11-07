<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $map = [
            'Electronics' => [
                ['name' => 'Mobile Phones', 'description' => 'Smartphones and mobile accessories.'],
                ['name' => 'Laptops', 'description' => 'Notebooks for work and creative tasks.'],
            ],
            'Fashion' => [
                ['name' => "Men's Clothing", 'description' => 'Casual and formal wear for men.'],
                ['name' => "Women's Clothing", 'description' => 'Everyday fashion for women.'],
            ],
            'Home & Living' => [
                ['name' => 'Furniture', 'description' => 'Desks, chairs, and living room pieces.'],
            ],
        ];

        foreach ($map as $categoryName => $subcategories) {
            $category = Category::query()->where('name', $categoryName)->firstOrFail();

            foreach ($subcategories as $subcategory) {
                Subcategory::query()->updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'slug' => Str::slug($subcategory['name']),
                    ],
                    [
                        'name' => $subcategory['name'],
                        'description' => $subcategory['description'],
                    ],
                );
            }
        }
    }
}
