<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\SubcategoryService;
use Illuminate\Http\JsonResponse;

class CategorySubcategoryController extends Controller
{
    public function __construct(private SubcategoryService $subcategories) {}

    public function __invoke(Category $category): JsonResponse
    {
        return response()->json([
            'data' => $this->subcategories->optionsForCategory($category),
        ]);
    }
}
