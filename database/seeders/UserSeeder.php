<?php

namespace Database\Seeders;

use App\Enums\EnumStatus;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->create([
            'name' => 'Hoàng Hồng',
            'email' => 'dante@example.com',
            'password' => Hash::make('123456789'),
            'status' => EnumStatus::ACTIVE->value,
            'phone' => '0123456789',
            'address' => 'Đông Anh, Hà Nội',
        ]);

        User::factory()->count(20)->create();
    }
}
