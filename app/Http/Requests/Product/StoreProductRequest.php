<?php

namespace App\Http\Requests\Product;

use App\Models\Subcategory;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'subcategory_id' => ['required', 'integer', 'exists:subcategories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'old_price' => ['required', 'numeric', 'min:0'],
            'new_price' => ['required', 'numeric', 'min:0', 'lte:old_price'],
        ];
    }

    /**
     * @return array<int, callable(Validator): void>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->hasAny(['category_id', 'subcategory_id'])) {
                    return;
                }

                $belongsToCategory = Subcategory::query()
                    ->whereKey($this->integer('subcategory_id'))
                    ->where('category_id', $this->integer('category_id'))
                    ->exists();

                if (! $belongsToCategory) {
                    $validator->errors()->add(
                        'subcategory_id',
                        'The selected subcategory does not belong to the selected category.',
                    );
                }
            },
        ];
    }
}
