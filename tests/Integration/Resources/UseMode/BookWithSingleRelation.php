<?php

namespace Netsells\Http\Resources\Tests\Integration\Resources\UseMode;

use Netsells\Http\Resources\Json\JsonResource;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Book;

/**
 * @mixin Book
 */
class BookWithSingleRelation extends JsonResource
{
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
