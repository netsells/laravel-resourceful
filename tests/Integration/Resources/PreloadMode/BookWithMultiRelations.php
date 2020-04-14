<?php

namespace Netsells\Http\Resources\Tests\Integration\Resources\PreloadMode;

use Netsells\Http\Resources\Json\JsonResource;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Book;

/**
 * @mixin Book
 */
class BookWithMultiRelations extends JsonResource
{
    public function preloads()
    {
        return $this->preload('author', 'genres');
    }

    public function toArray($request)
    {
        return [];
    }
}
