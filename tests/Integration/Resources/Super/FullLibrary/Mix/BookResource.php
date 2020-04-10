<?php

namespace Netsells\Http\Resources\Tests\Integration\Resources\Super\FullLibrary\Mix;

use Netsells\Http\Resources\Json\JsonResource;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Book;

/**
 * @mixin Book
 */
class BookResource extends JsonResource
{
    public function preloads()
    {
        return $this->preload('author');
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'isbn' => $this->isbn,
            'title' => $this->title,
            'author' => AuthorResource::make($this->author),
            'genres' => $this->use('genres', function () {
                return GenreResource::collection($this->genres);
            }),
        ];
    }
}
