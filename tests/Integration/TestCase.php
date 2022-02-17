<?php

namespace Netsells\Http\Resources\Tests\Integration;

use Faker\Generator as Faker;
use Illuminate\Database\Connection;
use Netsells\Http\Resources\Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * @var Faker
     */
    protected $faker;

    public function setUp(): void
    {
        parent::setUp();

        // $this->artisan('migrate', ['--database' => 'testing']);

        $this->faker = $this->app->make(Faker::class);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
    }

    protected function withQueryLog(mixed &$queryLog, callable $fn): mixed
    {
        /** @var Connection $connection */
        $connection = $this->app->get(Connection::class);
        $connection->enableQueryLog();
        $connection->flushQueryLog();

        return tap($fn(), function () use ($connection, &$queryLog) {
            $queryLog = $connection->getQueryLog();
            $connection->disableQueryLog();
        });
    }
}
