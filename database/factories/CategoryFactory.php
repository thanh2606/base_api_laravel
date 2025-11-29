<?php

namespace Database\Factories;

use App\Enums\EnumStatus;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(6, true),
            'slug' => $this->faker->slug(),
            'desc' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(3, true),
            'meta_title' => $this->faker->sentence(),
            'meta_desc' => $this->faker->sentence(),
            'meta_keywords' => $this->faker->words(3, true),
            'status' => EnumStatus::ACTIVE->value,
            'parent_id' => null,
            'author_id' => 1,
            'image_id' => null,
            'type' => rand(1, 2),
        ];
    }
}
