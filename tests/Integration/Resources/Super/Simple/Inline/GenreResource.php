<?php

namespace Netsells\Http\Resources\Tests\Integration\Resources\Super\Simple\Inline;

use Netsells\Http\Resources\Json\JsonResource;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Genre;

/**
 * @mixin Genre
 */
class GenreResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'fiction' => $this->fiction,
        ];
    }
}
