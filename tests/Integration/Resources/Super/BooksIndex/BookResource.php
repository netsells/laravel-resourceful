<?php

namespace Netsells\Http\Resources\Tests\Integration\Resources\Super\BooksIndex;

use Illuminate\Support\Collection;
use Netsells\Http\Resources\Json\JsonResource;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Book;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Genre;

/**
 * @mixin Book
 */
class BookResource extends JsonResource
{
    public function preloads()
    {
        $preloads = [
            $this->preload('genres'),
        ];

        if ($this->shelf_id) {
            $preloads[] = $this->preload('shelf.library');
        }

        return $preloads;
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'isbn' => $this->isbn,
            'title' => $this->title,
            'genres' => $this->genres->map(function (Genre $genre) {
                return $genre->name;
            }),
            'location' => $this->when($this->shelf_id, function () {
                return implode(', ', [
                    $this->shelf->row.$this->shelf->column,
                    $this->shelf->library->name,
                    $this->shelf->library->city,
                ]);
            }, 'Not in stock'),
            'authors' => $this->use(['coauthors', 'author'], function () {
                return AuthorResource::collection(
                    Collection::make([$this->author])->concat($this->coauthors)
                );
            }),
            'related_books' => $this->many('relatedBooks', BookResource::class),
        ];
    }
}
