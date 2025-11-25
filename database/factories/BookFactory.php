<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function definition()
    {
        $total = $this->faker->numberBetween(1,5);
        return [
            'title' => $this->faker->sentence(3),
            'author' => $this->faker->name,
            'genre' => $this->faker->word,
            'isbn' => $this->faker->unique()->isbn13,
            'published_at' => $this->faker->date(),
            'copies_total' => $total,
            'copies_available' => $total,
        ];
    }
}
