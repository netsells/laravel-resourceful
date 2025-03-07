<?php

namespace Netsells\Http\Resources\Tests\Integration;

use Akamon\MockeryCallableMock\MockeryCallableMock;
use Illuminate\Http\Resources\MissingValue;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Book;
use Netsells\Http\Resources\Tests\Integration\Resources\UseMode\BookWithMissingRelation;
use Netsells\Http\Resources\Tests\Integration\Resources\UseMode\BookWithMultiRelations;
use Netsells\Http\Resources\Tests\Integration\Resources\UseMode\BookWithSingleRelation;

class UseModeTest extends TestCase
{
    public function test_use_loads_and_provides_single_relation()
    {
        /** @var Book $book */
        $book = Book::factory()->forAuthor()->create()->fresh();

        BookWithSingleRelation::make($book)
            ->withCallback(
                $this->toCallback(function ($author) use ($book) {
                    return $author === $book->author;
                })
            )
            ->response();
    }

    public function test_use_loads_and_provides_multiple_relations()
    {
        /** @var Book $book */
        $book = Book::factory()->forAuthor()->create()->fresh();

        BookWithMultiRelations::make($book)
            ->withCallback(
                $this->toCallback(function ($author, $genres) use ($book) {
                    return $author === $book->author
                        && $genres === $book->genres;
                })
            )
            ->response();
    }

    public function test_use_skips_missing_value()
    {
        /** @var Book $book */
        $book = Book::factory()->forAuthor()->create()->fresh();

        $response = BookWithMissingRelation::make($book)
            ->withCallback(
                $this->toCallback(function ($shelf) {
                    return $shelf === null;
                }, function () {
                    return new MissingValue();
                })
            )
            ->response();

        $this->assertEquals(['data' => [
            'normal_field' => 'hello',
            'null_field' => null,
        ]], $response->getData(true));
    }

    protected function toCallback(callable $matcher, ?callable $returning = null): MockeryCallableMock
    {
        return tap(new MockeryCallableMock(), function (MockeryCallableMock $callback) use ($matcher, $returning) {
            $expectation = $callback->shouldBeCalled()
                ->once()
                ->withArgs($matcher);

            if ($returning) {
                $expectation->andReturnUsing($returning);
            }
        });
    }
}
