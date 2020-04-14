<?php

namespace Netsells\Http\Resources\Tests\Integration;

use Netsells\Http\Resources\Json\JsonResource;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Book;

/**
 * @mixin Book
 */
class BookWithSingleRelation extends JsonResource {

    protected $callback;

    public function toArray($request)
    {
        return [
            'author' => $this->use('author', $this->callback),
        ];
    }

    public function withCallback(callable $callback): self
    {
        $this->callback = $callback;

        return $this;
    }
}

/**
 * @mixin Book
 */
class BookWithMultiRelations extends JsonResource {

    protected $callback;

    public function toArray($request)
    {
        return [
            'author_with_genres' => $this->use(['author', 'genres'], $this->callback),
        ];
    }

    public function withCallback(callable $callback): self
    {
        $this->callback = $callback;

        return $this;
    }
}

class UseModeTest extends TestCase
{
    public function testUseLoadsAndProvidesSingleRelation()
    {
        $called = false;
        $providedArgs = [];

        /** @var Book $book */
        $book = factory(Book::class)->with('author')->create()->fresh();

        BookWithSingleRelation::make($book)
            ->withCallback(function () use (&$called, &$providedArgs) {
                $called = true;
                $providedArgs = func_get_args();
            })
            ->response();

        $this->assertTrue($called);
        $this->assertCount(1, $providedArgs);
        $this->assertEquals($book->author, $providedArgs[0]);
    }

    public function testUseLoadsAndProvidesMultipleRelations()
    {
        $called = false;
        $providedArgs = [];

        /** @var Book $book */
        $book = factory(Book::class)->with('author')->create()->fresh();

        BookWithMultiRelations::make($book)
            ->withCallback(function () use (&$called, &$providedArgs) {
                $called = true;
                $providedArgs = func_get_args();
            })
            ->response();

        $this->assertTrue($called);
        $this->assertCount(2, $providedArgs);
        $this->assertEquals($book->author, $providedArgs[0]);
        $this->assertEquals($book->genres, $providedArgs[1]);
    }
}
