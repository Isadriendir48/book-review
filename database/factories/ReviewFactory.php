<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $createdAt = fake()->dateTimeBetween('-2 years');
        $updatedAt = fake()->dateTimeBetween($createdAt);

        return [
            'book_id' => null,
            'review' => fake()->paragraph,
            'rating' => fake()->numberBetween(1, 5),
            'created_at' => $createdAt,
            'updated_at' => $updatedAt
        ];
    }

    public function good(): ReviewFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'rating' => fake()->numberBetween(4, 5)
            ];
        });
    }

    public function average(): ReviewFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'rating' => fake()->numberBetween(2, 5)
            ];
        });
    }

    public function bad(): ReviewFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'rating' => fake()->numberBetween(1, 3)
            ];
        });
    }
}
