<?php

namespace Netsells\Http\Resources\Tests\Integration;

use Akamon\MockeryCallableMock\MockeryCallableMock;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Book;
use Netsells\Http\Resources\Tests\Integration\Resources\UseMode\BookWithMultiRelations;
use Netsells\Http\Resources\Tests\Integration\Resources\UseMode\BookWithSingleRelation;

class UseModeTest extends TestCase
{
    public function testUseLoadsAndProvidesSingleRelation()
    {
        /** @var Book $book */
        $book = factory(Book::class)->with('author')->create()->fresh();

        BookWithSingleRelation::make($book)
            ->withCallback(
                $this->toCallback(function ($author) use ($book) {
                    return $author === $book->author;
                })
            )
            ->response();
    }

    public function testUseLoadsAndProvidesMultipleRelations()
    {
        /** @var Book $book */
        $book = factory(Book::class)->with('author')->create()->fresh();

        BookWithMultiRelations::make($book)
            ->withCallback(
                $this->toCallback(function ($author, $genres) use ($book) {
                    return $author === $book->author
                        && $genres === $book->genres;
                })
            )
            ->response();
    }

    protected function toCallback(callable $matcher): MockeryCallableMock
    {
        return tap(new MockeryCallableMock(), function (MockeryCallableMock $callback) use ($matcher) {
            $callback->shouldBeCalled()
                ->once()
                ->withArgs($matcher);
        });
    }
}
