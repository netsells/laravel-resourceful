<?php

namespace Netsells\Http\Resources\Tests\Integration\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Book;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Genre;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'isbn' => $this->faker->isbn10(),
            'title' => $this->faker->words(4, true),
            'text' => $this->faker->paragraphs(3, true),
        ];
    }

    public function configure(): self
    {
        return $this->afterCreating(function (Book $book) {
            $book->genres()->saveMany(
                Genre::query()->limit($this->faker->numberBetween(1, 3))->inRandomOrder()->get()
            );
        });
    }
}