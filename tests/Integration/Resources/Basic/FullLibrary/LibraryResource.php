<?php

namespace Netsells\Http\Resources\Tests\Integration\Resources\Basic\FullLibrary;

use Illuminate\Http\Resources\Json\JsonResource;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Library;

/**
 * @mixin Library
 */
class LibraryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'city' => $this->city,
            'shelves' => ShelfResource::collection($this->shelves),
        ];
    }
}
