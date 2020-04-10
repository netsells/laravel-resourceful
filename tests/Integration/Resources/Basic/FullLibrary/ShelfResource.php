<?php

namespace Netsells\Http\Resources\Tests\Integration\Resources\Basic\FullLibrary;

use Illuminate\Http\Resources\Json\JsonResource;
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
            'location' => $this->row . $this->column,
            'books' => BookResource::collection($this->books),
        ];
    }
}
