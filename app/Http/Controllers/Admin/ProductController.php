<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function __construct(private ProductService $products) {}

    public function index(Request $request): Response
    {
        $search = $request->string('search')->trim()->toString();

        return Inertia::render('admin/products/Index', [
            'products' => $this->products
                ->paginate($search)
                ->through(fn (Product $product) => $product->toAdminListArray()),
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/products/Create', [
            'categories' => $this->products->categoryOptions(),
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $this->products->create(
            $request->safe()->except(['image', 'category_id']),
            $request->file('image'),
        );

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Product created successfully.'),
        ]);

        return to_route('admin.products.index');
    }

    public function edit(Product $product): Response
    {
        return Inertia::render('admin/products/Edit', [
            'product' => $product->toAdminFormArray(),
            'categories' => $this->products->categoryOptions(),
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $this->products->update(
            $product,
            $request->safe()->except(['image', 'category_id']),
            $request->file('image'),
        );

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Product updated successfully.'),
        ]);

        return to_route('admin.products.index');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->products->delete($product);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Product deleted successfully.'),
        ]);

        return to_route('admin.products.index');
    }
}
