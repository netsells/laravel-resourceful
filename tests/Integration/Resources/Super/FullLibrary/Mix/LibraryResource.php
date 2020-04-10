<?php

namespace Netsells\Http\Resources\Tests\Integration\Resources\Super\FullLibrary\Mix;

use Netsells\Http\Resources\Json\JsonResource;
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
            'shelves' => $this->many('shelves', ShelfResource::class),
        ];
    }
}
