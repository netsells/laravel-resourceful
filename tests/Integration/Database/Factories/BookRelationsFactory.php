<?php

use Faker\Generator as Faker;
use Makeable\LaravelFactory\Factory;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Book;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Library;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Author;
use Illuminate\Database\Query\Builder as QueryBuilder;

///** @var Factory $factory */
//$factory->afterCreatingState(Library::class, 'coauthors', function (Library $library, Faker $faker) {
//    Book::query()->each(function (Book $book) use ($faker) {
//        $otherAuthors = Author::query()
//            ->inRandomOrder()
//            ->limit($faker->numberBetween(1, 3))
//            ->where('id', '!=', $book->author_id)
//            ->get();
//
//        $book->coauthors()->saveMany($otherAuthors);
//    });
//});
//
///** @var Factory $factory */
//$factory->afterCreatingState(Library::class, 'related_books', function (Library $library, Faker $faker) {
//    Book::query()->each(function (Book $book) use ($faker) {
//        $alreadyExistsInRelatedBooks = Book::query()->from('related_books')
//            ->where('book_id', $book->id)
//            ->orWhere('related_book_id', $book->id)
//            ->exists();
//
//        if ($alreadyExistsInRelatedBooks) {
//            return;
//        }
//
//        $book->relatedBooks()->saveMany(
//            Book::query()
//                ->inRandomOrder()
//                ->limit($faker->numberBetween(1, 3))
//                ->where('id', '!=', $book->id)
//                ->get()
//        );
//    });
//});
