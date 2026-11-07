<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subcategory\StoreSubcategoryRequest;
use App\Http\Requests\Subcategory\UpdateSubcategoryRequest;
use App\Models\Subcategory;
use App\Services\SubcategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SubcategoryController extends Controller
{
    public function __construct(private SubcategoryService $subcategories) {}

    public function index(Request $request): Response
    {
        $search = $request->string('search')->trim()->toString();

        return Inertia::render('admin/subcategories/Index', [
            'subcategories' => $this->subcategories->paginate($search),
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/subcategories/Create', [
            'categories' => $this->subcategories->categoryOptions(),
        ]);
    }

    public function store(StoreSubcategoryRequest $request): RedirectResponse
    {
        $this->subcategories->create($request->validated());

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Subcategory created successfully.'),
        ]);

        return to_route('admin.subcategories.index');
    }

    public function edit(Subcategory $subcategory): Response
    {
        return Inertia::render('admin/subcategories/Edit', [
            'subcategory' => $subcategory,
            'categories' => $this->subcategories->categoryOptions(),
        ]);
    }

    public function update(UpdateSubcategoryRequest $request, Subcategory $subcategory): RedirectResponse
    {
        $this->subcategories->update($subcategory, $request->validated());

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Subcategory updated successfully.'),
        ]);

        return to_route('admin.subcategories.index');
    }

    public function destroy(Subcategory $subcategory): RedirectResponse
    {
        if (! $this->subcategories->delete($subcategory)) {
            Inertia::flash('toast', [
                'type' => 'error',
                'message' => __('Cannot delete a subcategory that has products.'),
            ]);

            return back();
        }

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Subcategory deleted successfully.'),
        ]);

        return to_route('admin.subcategories.index');
    }
}
