<?php

namespace Database\Factories;

use App\Enums\EnumStatus;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class AdminFactory extends Factory
{
    protected $model = Admin::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'), // default password for all seeded admins
            'status' => EnumStatus::ACTIVE->value,
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
        ];
    }
}
