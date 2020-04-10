<?php

namespace Netsells\Http\Resources\Tests\Integration;

use Netsells\Http\Resources\Tests\Integration\Database\Models\Library;
use Netsells\Http\Resources\Tests\Integration\Resources\Basic;
use Netsells\Http\Resources\Tests\Integration\Resources\Super;

class SimpleResourceTest extends ResourceTestCase
{
    protected $dumpsQueryCount = false;

    public function resourceProvider(): array
    {
        return [
            [ Library::class, ['simple'], Basic\Simple\LibraryResource::class, Super\Simple\LibraryResource::class ],
            [ Library::class, ['simple'], Basic\Simple\LibraryResource::class, Super\Simple\Preload\LibraryResource::class ],
            [ Library::class, ['simple'], Basic\Simple\LibraryResource::class, Super\Simple\Inline\LibraryResource::class ],
            [ Library::class, ['simple'], Basic\Simple\LibraryResource::class, Super\Simple\Callback\LibraryResource::class ],
            [ Library::class, ['simple'], Basic\Simple\LibraryResource::class, Super\Simple\Mix\LibraryResource::class ],
        ];
    }
}
