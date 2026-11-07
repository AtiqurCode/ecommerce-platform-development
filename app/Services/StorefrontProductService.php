<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class StorefrontProductService
{
    /**
     * @return LengthAwarePaginator<int, array<string, mixed>>
     */
    public function groupedCatalogue(int $perPage = 6): LengthAwarePaginator
    {
        return Subcategory::query()
            ->with(['category:id,name', 'products' => function ($query) {
                $query->latest();
            }])
            ->whereHas('products')
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString()
            ->through(fn (Subcategory $subcategory) => [
                'id' => $subcategory->id,
                'name' => $subcategory->name,
                'category' => $subcategory->category?->name,
                'products' => $subcategory->products
                    ->map(fn (Product $product) => $product->toStorefrontCardArray())
                    ->values(),
            ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function details(Product $product): array
    {
        $product->loadMissing('subcategory.category');

        return $product->toStorefrontDetailArray();
    }
}
