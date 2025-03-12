<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuItemRequest extends FormRequest
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
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric'],
            'image_url' => ['required', 'file', 'mimes:jpeg,jpg,png', 'max:1024'],
            'is_published' => ['required', 'boolean'],
            'is_available' => ['required', 'boolean'],
            'description' => ['nullable', 'string'],
        ];
    }
}
