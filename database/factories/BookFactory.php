<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
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
            'title' => fake()->sentence(3),
            'author' => fake()->name,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt
        ];
    }
}
