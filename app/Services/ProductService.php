<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function __construct(
        private SlugGenerator $slugs,
        private ProductImageService $images,
    ) {}

    /**
     * @return LengthAwarePaginator<int, Product>
     */
    public function paginate(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return Product::query()
            ->with(['subcategory.category'])
            ->search($search)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * @return Collection<int, Category>
     */
    public function categoryOptions(): Collection
    {
        return Category::query()
            ->with(['subcategories:id,category_id,name'])
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    /**
     * @param  array{subcategory_id: int, name: string, description?: string|null, old_price: mixed, new_price: mixed}  $data
     */
    public function create(array $data, UploadedFile $image): Product
    {
        return Product::query()->create([
            ...$data,
            'slug' => $this->slugs->unique(Product::class, $data['name']),
            'image' => $this->images->store($image),
        ]);
    }

    /**
     * @param  array{subcategory_id: int, name: string, description?: string|null, old_price: mixed, new_price: mixed}  $data
     */
    public function update(Product $product, array $data, ?UploadedFile $image = null): Product
    {
        $payload = [
            ...$data,
            'slug' => $this->slugs->unique(Product::class, $data['name'], $product->id),
        ];

        if ($image !== null) {
            $payload['image'] = $this->images->replace($product->image, $image);
        }

        $product->update($payload);

        return $product->refresh();
    }

    public function delete(Product $product): void
    {
        DB::transaction(function () use ($product): void {
            $imagePath = $product->image;
            $product->delete();
            $this->images->delete($imagePath);
        });
    }
}
