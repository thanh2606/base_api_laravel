<?php

namespace App\Http\Requests\Admin\Profile;

use App\Enums\EnumStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfile extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email,'.$this->route('admin')->id],
            'status' => ['required', Rule::in(EnumStatus::cases())],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'image_id' => ['nullable', 'exists:medias,id'],
            'role_id' => ['nullable', 'exists:roles,id'],
        ];
    }
}
