<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductImageService
{
    public function store(UploadedFile $image): string
    {
        return $image->store('products', 'public');
    }

    public function replace(?string $currentPath, UploadedFile $image): string
    {
        $path = $this->store($image);

        $this->delete($currentPath);

        return $path;
    }

    public function delete(?string $path): void
    {
        if ($path === null || $path === '') {
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
