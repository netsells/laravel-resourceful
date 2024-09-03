<?php

namespace Netsells\Http\Resources\Tests\Integration;

use Netsells\Http\Resources\Tests\Integration\Database\Models\Book;
use Netsells\Http\Resources\Tests\Integration\Resources\PreloadMode\BookWithMultiRelations;
use Netsells\Http\Resources\Tests\Integration\Resources\PreloadMode\BookWithSingleRelation;

class PreloadModeTest extends TestCase
{
    public function test_preload_loads_single_relation_on_resource()
    {
        /** @var Book $book */
        $book = Book::factory()->forAuthor()->create()->fresh();

        BookWithSingleRelation::make($book)->response();

        $this->assertTrue($book->relationLoaded('author'));
    }

    public function test_preload_loads_multiple_relations_on_resource()
    {
        /** @var Book $book */
        $book = Book::factory()->forAuthor()->create()->fresh();

        BookWithMultiRelations::make($book)->response();

        $this->assertTrue($book->relationLoaded('author'));
        $this->assertTrue($book->relationLoaded('genres'));
    }

    public function test_preload_loads_single_relation_on_resource_collection()
    {
        /** @var Book[] $books */
        $books = Book::factory()->count(3)->forAuthor()->create()->fresh();

        BookWithSingleRelation::collection($books)->response();

        foreach ($books as $book) {
            $this->assertTrue($book->relationLoaded('author'));
        }
    }

    public function test_preload_loads_multiple_relations_on_resource_collection()
    {
        /** @var Book[] $books */
        $books = Book::factory()->count(3)->forAuthor()->create()->fresh();

        BookWithMultiRelations::collection($books)->response();

        foreach ($books as $book) {
            $this->assertTrue($book->relationLoaded('author'));
            $this->assertTrue($book->relationLoaded('genres'));
        }
    }
}
