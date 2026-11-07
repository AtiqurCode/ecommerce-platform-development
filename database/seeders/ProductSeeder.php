<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Subcategory;
use App\Support\EcommerceCatalog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Storage::disk('public')->deleteDirectory('products');
        Storage::disk('public')->makeDirectory('products');

        Product::query()->delete();

        foreach (EcommerceCatalog::seedProducts() as $product) {
            $subcategory = Subcategory::query()
                ->where('name', $product['subcategory'])
                ->firstOrFail();

            $slug = Str::slug($product['name']);

            Product::query()->create([
                'subcategory_id' => $subcategory->id,
                'name' => $product['name'],
                'slug' => $slug,
                'description' => $product['description'],
                'image' => $this->storeProductImage($slug, $product['image_url'], $product['name']),
                'old_price' => $product['old_price'],
                'new_price' => $product['new_price'],
            ]);
        }
    }

    private function storeProductImage(string $slug, string $imageUrl, string $label): string
    {
        $relativePath = "products/{$slug}.jpg";

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'User-Agent' => 'EcommercePlatformSeeder/1.0',
                    'Accept' => 'image/*',
                ])
                ->get($imageUrl);

            if ($response->successful() && strlen($response->body()) > 1000) {
                Storage::disk('public')->put($relativePath, $response->body());

                return $relativePath;
            }
        } catch (\Throwable) {
            // Fall through to a local placeholder when the CDN is unreachable.
        }

        return $this->createPlaceholderImage($slug, $label);
    }

    private function createPlaceholderImage(string $slug, string $label): string
    {
        $relativePath = "products/{$slug}.jpg";
        $absolutePath = Storage::disk('public')->path($relativePath);

        $image = imagecreatetruecolor(800, 800);
        $background = imagecolorallocate($image, 18, 18, 18);
        $accent = imagecolorallocate($image, 214, 40, 40);
        $textColor = imagecolorallocate($image, 243, 243, 241);

        imagefilledrectangle($image, 0, 0, 800, 800, $background);
        imagefilledrectangle($image, 0, 760, 800, 800, $accent);

        $text = Str::limit($label, 36, '');
        imagestring($image, 5, 40, 380, $text, $textColor);

        imagejpeg($image, $absolutePath, 90);
        imagedestroy($image);

        return $relativePath;
    }
}
