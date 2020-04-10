<?php

namespace Netsells\Http\Resources\Tests\Integration\Resources\Super\Simple\Callback;

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
            'shelves' => $this->use('shelves', function () {
                return ShelfResource::collection($this->shelves);
            }),
        ];
    }
}
