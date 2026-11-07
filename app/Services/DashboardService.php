<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Support\Collection;

class DashboardService
{
    /**
     * @return array{categories: int, subcategories: int, products: int}
     */
    public function stats(): array
    {
        return [
            'categories' => Category::query()->count(),
            'subcategories' => Subcategory::query()->count(),
            'products' => Product::query()->count(),
        ];
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function recentProducts(int $limit = 5): Collection
    {
        return Product::query()
            ->with(['subcategory.category'])
            ->latest()
            ->limit($limit)
            ->get()
            ->map(fn (Product $product) => $product->toDashboardArray());
    }
}
