<?php

namespace Netsells\Http\Resources\Tests\Integration\Resources\Basic\BooksIndex;

use Illuminate\Http\Resources\Json\JsonResource;
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
