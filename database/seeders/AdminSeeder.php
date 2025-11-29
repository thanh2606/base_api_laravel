<?php

namespace Database\Seeders;

use App\Enums\EnumStatus;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the main admin account
        Admin::query()->create([
            'name' => 'Hoàng Hồng',
            'email' => 'dante@example.com',
            'password' => Hash::make('123456789'),
            'status' => EnumStatus::ACTIVE->value,
            'phone' => '0123456789',
            'address' => 'Đông Anh, Hà Nội',
        ]);

        // Create 100 additional admin records
        Admin::factory()->count(20)->create();
    }
}
