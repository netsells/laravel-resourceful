<?php

use Faker\Generator as Faker;
use Makeable\LaravelFactory\Factory;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Book;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Genre;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Library;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Author;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Shelf;

/** @var Factory $factory */
$factory->define(Library::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'city' => $faker->city,
    ];
});

$factory->define(Author::class, function (Faker $faker) {
    return [
        'firstname' => $faker->firstName,
        'lastname' => $faker->lastName,
    ];
});

$factory->define(Shelf::class, function (Faker $faker) {
    return [
        'row' => $faker->randomDigit,
        'column' => $faker->randomLetter,
    ];
});

$factory->define(Book::class, function (Faker $faker) {
    return [
        'isbn' => $faker->isbn10,
        'title' => $faker->words(4, true),
        'text' => $faker->paragraphs(3, true),
    ];
});

$factory->afterCreating(Book::class, function (Book $book, Faker $faker) {
    $book->genres()->saveMany(
        Genre::query()->limit($faker->numberBetween(1, 3))->inRandomOrder()->get()
    );
});
