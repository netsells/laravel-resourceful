<?php

namespace Netsells\Http\Resources\Tests\Integration;

use Faker\Generator as Faker;
use Illuminate\Database\Connection;
use Makeable\LaravelFactory\Factory;
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

        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
        $this->app->make(Factory::class)->load(__DIR__ . '/Database/Factories');
        $this->artisan('migrate', ['--database' => 'testing']);

        $this->faker = $this->app->make(Faker::class);
    }

    /**
     * @param array $queryLog
     * @param callable $fn
     * @return mixed
     */
    protected function withQueryLog(&$queryLog, callable $fn)
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
