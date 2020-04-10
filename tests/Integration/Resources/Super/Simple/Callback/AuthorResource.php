<?php

namespace Netsells\Http\Resources\Tests\Integration\Resources\Super\Simple\Callback;

use Netsells\Http\Resources\Json\JsonResource;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Author;

/**
 * @mixin Author
 */
class AuthorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
        ];
    }
}
