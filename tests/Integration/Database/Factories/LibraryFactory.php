<?php

namespace Netsells\Http\Resources\Tests\Integration\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Library;

class LibraryFactory extends Factory
{
    protected $model = Library::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'city' => $this->faker->city(),
        ];
    }
}