<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'name' => 'required|string|min:3|max:225',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|gte:price',
            'options' => 'nullable|array',
            'rating' => 'nullable|numeric|min:0|max:5',
            'featured' => 'boolean',
            'status' => 'required|in:active,draft,archived',
            'store_id' => 'nullable',
            'category_id' => 'required|exists:categories,id',
        ];
    }
}
