<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuItemRequest extends FormRequest
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
        $rules = [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric'],
            'is_available' => ['required', 'boolean'],
            'description' => ['nullable', 'string'],
        ];

        // Only apply image validation if a file is actually uploaded
        if ($this->hasFile('image_url')) {
            $rules['image_url'] = ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:1024'];
        } else {
            $rules['image_url'] = ['nullable'];
        }

        return $rules;
    }
}
