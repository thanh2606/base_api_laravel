<?php

namespace Database\Factories;

use App\Enums\EnumAttributeType;
use App\Enums\EnumStatus;
use App\Models\Attribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Attribute>
 */
class AttributeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'slug' => fake()->slug,
            'type' => EnumAttributeType::SELECT->value,
            'sort_order' => 1,
            'status' => EnumStatus::ACTIVE->value,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
