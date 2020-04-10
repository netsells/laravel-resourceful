<?php

namespace Netsells\Http\Resources\Tests\Integration\Resources\Super\Simple\Inline;

use Netsells\Http\Resources\Json\JsonResource;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Book;

/**
 * @mixin Book
 */
class BookResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'isbn' => $this->isbn,
            'title' => $this->title,
            'author' => $this->one('author', AuthorResource::class),
            'genres' => $this->many('genres', GenreResource::class),
        ];
    }
}
