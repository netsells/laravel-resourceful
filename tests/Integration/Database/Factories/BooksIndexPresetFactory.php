<?php

use Faker\Generator as Faker;
use Makeable\LaravelFactory\Factory;
use Makeable\LaravelFactory\FactoryBuilder;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Book;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Shelf;

/** @var Factory $factory */
$factory->preset(Book::class, 'books_index', function (FactoryBuilder $book, Faker $faker) {
    return $book->with('author')
        ->odds('40%', function (FactoryBuilder $book) {
            return $book->with('books_index', 'shelf.library');
        });
});

/** @var Factory $factory */
$factory->preset(Shelf::class, 'books_index', function (FactoryBuilder $shelf, Faker $faker) {
    return $shelf->with('library')
        ->with($faker->numberBetween(1, 3), 'books')
        ->with(1, 'books.author');
});
