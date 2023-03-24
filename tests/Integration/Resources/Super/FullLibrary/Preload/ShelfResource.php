<?php

namespace Netsells\Http\Resources\Tests\Integration\Resources\Super\FullLibrary\Preload;

use Netsells\Http\Resources\Json\JsonResource;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Shelf;

/**
 * @mixin Shelf
 */
class ShelfResource extends JsonResource
{
    public function preloads()
    {
        return $this->preload('books');
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'location' => $this->row.$this->column,
            'books' => BookResource::collection($this->books),
        ];
    }
}
