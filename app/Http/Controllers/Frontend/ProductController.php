<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\StorefrontProductService;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function __construct(private StorefrontProductService $storefront) {}

    public function index(): Response
    {
        return Inertia::render('frontend/products/Index', [
            'groups' => $this->storefront->groupedCatalogue(),
        ]);
    }

    public function show(Product $product): Response
    {
        return Inertia::render('frontend/products/Show', [
            'product' => $this->storefront->details($product),
        ]);
    }
}
