<?php

namespace Netsells\Http\Resources\Tests\Integration;

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Author;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Book;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Library;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Shelf;

/**
 * @property Faker $faker
 */
trait Presets
{
    public function fullLibraryFactory(?int $count = null): Factory
    {
        return Library::factory()->count($count)->has(
            Shelf::factory()->count(5)->has(
                Book::factory()->count($this->faker->numberBetween(2, 4))
                    ->for(Author::factory())
            )
        );
    }
}
