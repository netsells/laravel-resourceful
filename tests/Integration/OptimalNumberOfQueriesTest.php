<?php

namespace Netsells\Http\Resources\Tests\Integration;

use Netsells\Http\Resources\Tests\Integration\Database\Models\Library;
use Netsells\Http\Resources\Tests\Integration\Resources\Basic;
use Netsells\Http\Resources\Tests\Integration\Resources\Super;

class OptimalNumberOfQueriesTest extends TestCase
{
    use Presets;

    public static function resourceProvider(): array
    {
        return [
            [Basic\FullLibrary\LibraryResource::class, Super\FullLibrary\Preload\LibraryResource::class],
            [Basic\FullLibrary\LibraryResource::class, Super\FullLibrary\Inline\LibraryResource::class],
            [Basic\FullLibrary\LibraryResource::class, Super\FullLibrary\Callback\LibraryResource::class],
            [Basic\FullLibrary\LibraryResource::class, Super\FullLibrary\Mix\LibraryResource::class],
        ];
    }

    /**
     * @dataProvider resourceProvider
     */
    public function test_creates_as_many_queries_as_eager_loading_resource_collection(string $basicClass, string $superClass)
    {
        $this->fullLibraryFactory()->create();

        // count how many queries are performed when performing eager loading
        $libraries = $this->withQueryLog($optimalQueryLog, function () {
            return Library::query()
                ->with([
                    'shelves.books',
                    'shelves.books.author',
                    'shelves.books.genres',
                ])
                ->get();
        });

        // once eager loading has been performed, creating resource should run no queries
        $this->withQueryLog($zeroQueryLog, function () use ($basicClass, $libraries) {
            $basicClass::collection($libraries)->response();
        });

        $this->withQueryLog($normalQueryLog, function () use ($superClass) {
            $libraries = Library::query()->get();
            $superClass::collection($libraries)->response();
        });

        $this->assertEmpty($zeroQueryLog);
        $this->assertEquals(count($optimalQueryLog), count($normalQueryLog));
    }

    /**
     * @dataProvider resourceProvider
     */
    public function test_creates_as_many_queries_as_lazy_eager_loading_resource_collection(string $basicClass, string $superClass)
    {
        $this->fullLibraryFactory()->create();

        // count how many queries are performed when performing lazy eager loading
        $this->withQueryLog($optimalQueryLog, function () use ($basicClass) {
            $libraries = Library::query()->get();
            $libraries->loadMissing([
                'shelves.books',
                'shelves.books.author',
                'shelves.books.genres',
            ]);
            $basicClass::collection($libraries)->response();
        });

        $this->withQueryLog($normalQueryLog, function () use ($superClass) {
            $libraries = Library::query()->get();
            $superClass::collection($libraries)->response();
        });

        $this->assertEquals(count($optimalQueryLog), count($normalQueryLog));
    }

    /**
     * @dataProvider resourceProvider
     */
    public function test_creates_as_many_queries_as_eager_loading_resource(string $basicClass, string $superClass)
    {
        $this->fullLibraryFactory()->create();

        // count how many queries are performed when performing eager loading
        $library = $this->withQueryLog($optimalQueryLog, function () {
            return Library::query()
                ->with([
                    'shelves.books',
                    'shelves.books.author',
                    'shelves.books.genres',
                ])
                ->first();
        });

        // once eager loading has been performed, creating resource should run no queries
        $this->withQueryLog($zeroQueryLog, function () use ($basicClass, $library) {
            $basicClass::make($library)->response();
        });

        $this->withQueryLog($normalQueryLog, function () use ($superClass) {
            $library = Library::query()->first();
            $superClass::make($library)->response();
        });

        $this->assertEmpty($zeroQueryLog);
        $this->assertEquals(count($optimalQueryLog), count($normalQueryLog));
    }

    /**
     * @dataProvider resourceProvider
     */
    public function test_creates_as_many_queries_as_lazy_eager_loading_resource(string $basicClass, string $superClass)
    {
        $this->fullLibraryFactory()->create();

        // count how many queries are performed when performing lazy eager loading
        $this->withQueryLog($optimalQueryLog, function () use ($basicClass) {
            $library = Library::query()->first();
            $library->loadMissing([
                'shelves.books',
                'shelves.books.author',
                'shelves.books.genres',
            ]);
            $basicClass::make($library)->response();
        });

        $this->withQueryLog($normalQueryLog, function () use ($superClass) {
            $library = Library::query()->first();
            $superClass::make($library)->response();
        });

        $this->assertEquals(count($optimalQueryLog), count($normalQueryLog));
    }
}
