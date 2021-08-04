<?php

namespace Netsells\Http\Resources\Tests\Integration\Resources\UseMode;

use Netsells\Http\Resources\Json\JsonResource;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Book;

/**
 * @mixin Book
 */
class BookWithMissingRelation extends JsonResource
{
    protected $callback;

    public function toArray($request)
    {
        return [
            'normal_field' => 'hello',
            'null_field' => null,
            'location' => $this->use('shelf', $this->callback),
        ];
    }

    public function withCallback(callable $callback): self
    {
        $this->callback = $callback;

        return $this;
    }
}
