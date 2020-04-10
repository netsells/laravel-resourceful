<?php

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Book;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Library;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Author;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Genre;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Shelf;

/** @var Factory $factory */
$factory->afterCreatingState(Library::class, 'simple', function (Library $library, Faker $faker) {
    $library->shelves()->saveMany(
        factory(Shelf::class, 5)->state('simple')->create([
            'library_id' => $library->id,
        ])
    );
});

/** @var Factory $factory */
$factory->afterCreatingState(Shelf::class, 'simple', function (Shelf $shelf, Faker $faker) {
    $authors = factory(Author::class, 5)->state('simple')->create();
    $authors->flatMap(function (Author $author) {
        return $author->books;
    })->random(5)->each(function (Book $book) use ($shelf) {
        $book->shelf()->associate($shelf)->save();
    });
});

/** @var Factory $factory */
$factory->afterCreatingState(Author::class, 'simple', function (Author $author, Faker $faker) {
    $author->books()->saveMany(
        factory(Book::class, $faker->numberBetween(1, 3))->state('simple')->create([
            'author_id' => $author->id,
        ])
    );
});

/** @var Factory $factory */
$factory->afterCreatingState(Book::class, 'simple', function (Book $book, Faker $faker) {
    $book->genres()->saveMany(
        Genre::query()->limit($faker->numberBetween(1, 3))->inRandomOrder()->get()
    );
});
