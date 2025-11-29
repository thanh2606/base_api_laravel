<?php

namespace App\Http\Requests\Admin\Category;

use App\Enums\EnumCategoryType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:categories,slug,'.$this->route('category')->id],
            'desc' => ['nullable', 'string', 'max:500'],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
            'content' => ['nullable', 'string'],
            'status' => ['required', 'integer', 'in:0,1'],
            'image_id' => ['nullable', 'integer', 'exists:medias,id'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'meta_desc' => ['nullable', 'string', 'max:500'],
            'type' => ['required', Rule::in(EnumCategoryType::cases())],
        ];
    }
}
