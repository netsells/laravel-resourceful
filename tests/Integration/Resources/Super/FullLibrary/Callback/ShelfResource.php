<?php

namespace Netsells\Http\Resources\Tests\Integration\Resources\Super\FullLibrary\Callback;

use Netsells\Http\Resources\Json\JsonResource;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Shelf;

/**
 * @mixin Shelf
 */
class ShelfResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'location' => $this->row.$this->column,
            'books' => $this->use('books', function () {
                return BookResource::collection($this->books);
            }),
        ];
    }
}
