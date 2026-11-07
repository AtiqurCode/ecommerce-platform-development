<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $subcategory_id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string $image
 * @property string $old_price
 * @property string $new_price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Subcategory|null $subcategory
 */
#[Fillable([
    'subcategory_id',
    'name',
    'slug',
    'description',
    'image',
    'old_price',
    'new_price',
])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Subcategory, $this>
     */
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'old_price' => 'decimal:2',
            'new_price' => 'decimal:2',
        ];
    }

    public function imageUrl(): string
    {
        return asset('storage/'.$this->image);
    }

    /**
     * @param  Builder<Product>  $query
     * @return Builder<Product>
     */
    #[Scope]
    protected function search(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);

        if ($term === '') {
            return $query;
        }

        return $query->where(function (Builder $query) use ($term): void {
            $query->where('name', 'like', "%{$term}%")
                ->orWhere('slug', 'like', "%{$term}%")
                ->orWhereHas('subcategory', function (Builder $query) use ($term): void {
                    $query->where('name', 'like', "%{$term}%")
                        ->orWhereHas('category', function (Builder $query) use ($term): void {
                            $query->where('name', 'like', "%{$term}%");
                        });
                });
        });
    }

    /**
     * @return array<string, mixed>
     */
    public function toAdminListArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'old_price' => $this->old_price,
            'new_price' => $this->new_price,
            'image_url' => $this->imageUrl(),
            'category' => $this->subcategory?->category?->name,
            'subcategory' => $this->subcategory?->name,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toAdminFormArray(): array
    {
        $this->loadMissing('subcategory.category');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'old_price' => $this->old_price,
            'new_price' => $this->new_price,
            'category_id' => $this->subcategory?->category_id,
            'subcategory_id' => $this->subcategory_id,
            'image_url' => $this->imageUrl(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toDashboardArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'new_price' => $this->new_price,
            'image_url' => $this->imageUrl(),
            'category' => $this->subcategory?->category?->name,
            'subcategory' => $this->subcategory?->name,
            'created_at' => $this->created_at?->toDateString(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toStorefrontCardArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'old_price' => $this->old_price,
            'new_price' => $this->new_price,
            'image_url' => $this->imageUrl(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toStorefrontDetailArray(): array
    {
        $this->loadMissing('subcategory.category');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'old_price' => $this->old_price,
            'new_price' => $this->new_price,
            'image_url' => $this->imageUrl(),
            'category' => $this->subcategory?->category?->name,
            'subcategory' => $this->subcategory?->name,
            'meta_description' => str($this->description ?? $this->name)->limit(160)->toString(),
        ];
    }
}
