<?php

namespace Netsells\Http\Resources\Tests\Integration;

use Netsells\Http\Resources\Tests\Integration\Database\Models\Book;
use Netsells\Http\Resources\Tests\Integration\Resources\PreloadMode\BookWithMultiRelations;
use Netsells\Http\Resources\Tests\Integration\Resources\PreloadMode\BookWithSingleRelation;

class PreloadModeTest extends TestCase
{
    public function testPreloadLoadsSingleRelationOnResource()
    {
        /** @var Book $book */
        $book = factory(Book::class)->with('author')->create()->fresh();

        BookWithSingleRelation::make($book)->response();

        $this->assertTrue($book->relationLoaded('author'));
    }

    public function testPreloadLoadsMultipleRelationsOnResource()
    {
        /** @var Book $book */
        $book = factory(Book::class)->with('author')->create()->fresh();

        BookWithMultiRelations::make($book)->response();

        $this->assertTrue($book->relationLoaded('author'));
        $this->assertTrue($book->relationLoaded('genres'));
    }

    public function testPreloadLoadsSingleRelationOnResourceCollection()
    {
        /** @var Book[] $books */
        $books = factory(Book::class, 3)->with('author')->create()->fresh();

        BookWithSingleRelation::collection($books)->response();

        foreach ($books as $book) {
            $this->assertTrue($book->relationLoaded('author'));
        }
    }

    public function testPreloadLoadsMultipleRelationsOnResourceCollection()
    {
        /** @var Book[] $books */
        $books = factory(Book::class, 3)->with('author')->create()->fresh();

        BookWithMultiRelations::collection($books)->response();

        foreach ($books as $book) {
            $this->assertTrue($book->relationLoaded('author'));
            $this->assertTrue($book->relationLoaded('genres'));
        }
    }
}
