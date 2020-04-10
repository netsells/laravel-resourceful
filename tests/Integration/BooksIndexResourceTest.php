<?php

namespace Netsells\Http\Resources\Tests\Integration;

use Netsells\Http\Resources\Tests\Integration\Database\Models\Book;
use Netsells\Http\Resources\Tests\Integration\Resources\Basic;
use Netsells\Http\Resources\Tests\Integration\Resources\Super;

class BooksIndexResourceTest extends ResourceTestCase
{
    protected $dumpsQueryCount = true;

    public function resourceProvider(): array
    {
        return [
            [ Book::class, 'books_index', Basic\BooksIndex\BookResource::class, Super\BooksIndex\BookResource::class ],
        ];
    }
}
