<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class SubcategoryService
{
    public function __construct(private SlugGenerator $slugs) {}

    /**
     * @return LengthAwarePaginator<int, Subcategory>
     */
    public function paginate(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return Subcategory::query()
            ->with('category:id,name')
            ->withCount('products')
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
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    /**
     * @return Collection<int, Subcategory>
     */
    public function optionsForCategory(Category $category): Collection
    {
        return $category->subcategories()
            ->orderBy('name')
            ->get(['id', 'name', 'category_id']);
    }

    /**
     * @param  array{category_id: int, name: string, description?: string|null}  $data
     */
    public function create(array $data): Subcategory
    {
        return Subcategory::query()->create([
            ...$data,
            'slug' => $this->slugs->unique(
                Subcategory::class,
                $data['name'],
                null,
                ['category_id' => $data['category_id']],
            ),
        ]);
    }

    /**
     * @param  array{category_id: int, name: string, description?: string|null}  $data
     */
    public function update(Subcategory $subcategory, array $data): Subcategory
    {
        $subcategory->update([
            ...$data,
            'slug' => $this->slugs->unique(
                Subcategory::class,
                $data['name'],
                $subcategory->id,
                ['category_id' => $data['category_id']],
            ),
        ]);

        return $subcategory->refresh();
    }

    public function delete(Subcategory $subcategory): bool
    {
        if ($subcategory->hasProducts()) {
            return false;
        }

        $subcategory->delete();

        return true;
    }
}
