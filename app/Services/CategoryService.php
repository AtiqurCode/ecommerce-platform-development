<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryService
{
    public function __construct(private SlugGenerator $slugs) {}

    /**
     * @return LengthAwarePaginator<int, Category>
     */
    public function paginate(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return Category::query()
            ->withCount('subcategories')
            ->search($search)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * @param  array{name: string, description?: string|null}  $data
     */
    public function create(array $data): Category
    {
        return Category::query()->create([
            ...$data,
            'slug' => $this->slugs->unique(Category::class, $data['name']),
        ]);
    }

    /**
     * @param  array{name: string, description?: string|null}  $data
     */
    public function update(Category $category, array $data): Category
    {
        $category->update([
            ...$data,
            'slug' => $this->slugs->unique(Category::class, $data['name'], $category->id),
        ]);

        return $category->refresh();
    }

    public function delete(Category $category): bool
    {
        if ($category->hasSubcategories()) {
            return false;
        }

        $category->delete();

        return true;
    }
}
