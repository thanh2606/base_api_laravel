<?php

namespace App\Http\Requests\Admin\Profile;

use App\Enums\EnumStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class StoreAdminRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('admins', 'email')->whereNull('deleted_at')],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'status' => ['required', Rule::in(EnumStatus::cases())],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'image_id' => ['nullable', 'exists:medias,id'],
            'role_id' => ['nullable', 'exists:roles,id'],
        ];
    }

    public function toArray(): array
    {
        return [
            'name' => $this->string('name'),
            'email' => $this->string('email'),
            'password' => Hash::make($this->input('password')),
            'status' => $this->integer('status'),
            'phone' => $this->input('phone'),
            'address' => $this->input('address'),
            'image_id' => $this->input('image_id'),
            'role_id' => $this->input('role_id'),
        ];
    }
}
