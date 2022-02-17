<?php

namespace Netsells\Http\Resources\Tests\Integration\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Shelf;

class ShelfFactory extends Factory
{
    protected $model = Shelf::class;

    public function definition(): array
    {
        return [
            'row' => $this->faker->randomDigit(),
            'column' => $this->faker->randomLetter(),
        ];
    }
}