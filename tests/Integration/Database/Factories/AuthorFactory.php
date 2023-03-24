<?php

namespace Netsells\Http\Resources\Tests\Integration\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Author;

class AuthorFactory extends Factory
{
    protected $model = Author::class;

    public function definition(): array
    {
        return [
            'firstname' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
        ];
    }
}
