<?php

use Faker\Generator as Faker;
use Makeable\LaravelFactory\Factory;
use Makeable\LaravelFactory\FactoryBuilder;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Library;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Shelf;

/** @var Factory $factory */
$factory->preset(Library::class, 'full_library', function (FactoryBuilder $library, Faker $faker) {
    return $library->with(5, 'shelves', 'full_library');
});

/** @var Factory $factory */
$factory->preset(Shelf::class, 'full_library', function (FactoryBuilder $shelf, Faker $faker) {
    return $shelf->with($faker->numberBetween(2, 4), 'books')
        ->with(1, 'books.author');
});
