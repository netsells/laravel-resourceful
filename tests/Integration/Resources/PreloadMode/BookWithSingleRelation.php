<?php

namespace Netsells\Http\Resources\Tests\Integration\Resources\PreloadMode;

use Netsells\Http\Resources\Json\JsonResource;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Book;

/**
 * @mixin Book
 */
class BookWithSingleRelation extends JsonResource
{
    public function preloads()
    {
        return $this->preload('author');
    }

    public function toArray($request)
    {
        return [];
    }
}
