<?php

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Book;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Library;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Author;

/** @var Factory $factory */
$factory->afterCreatingState(Library::class, 'with_coauthors', function (Library $library, Faker $faker) {
    Book::query()->each(function (Book $book) use ($faker) {
        $otherAuthors = Author::query()
            ->inRandomOrder()
            ->limit($faker->numberBetween(1, 3))
            ->where('id', '!=', $book->author_id)
            ->get();

        $book->coauthors()->saveMany($otherAuthors);
    });
});
