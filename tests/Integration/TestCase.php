<?php

namespace Netsells\Http\Resources\Tests\Integration;

use Illuminate\Database\Connection;
use Netsells\Http\Resources\Tests\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
        $this->withFactories(__DIR__ . '/Database/Factories');
        $this->artisan('migrate', ['--database' => 'testing']);
    }

    /**
     * @param callable $fn
     * @param int $queryCount
     * @return mixed
     */
    protected function countingQueries(&$queryCount, callable $fn)
    {
        /** @var Connection $connection */
        $connection = $this->app->get(Connection::class);
        $connection->enableQueryLog();
        $connection->flushQueryLog();

        return tap($fn(), function () use ($connection, &$queryCount) {
            $queryCount = count($connection->getQueryLog());
            $connection->disableQueryLog();
        });
    }
}
