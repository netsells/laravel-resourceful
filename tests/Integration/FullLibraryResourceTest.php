<?php

namespace Netsells\Http\Resources\Tests\Integration;

use Netsells\Http\Resources\Tests\Integration\Database\Models\Library;
use Netsells\Http\Resources\Tests\Integration\Resources\Basic;
use Netsells\Http\Resources\Tests\Integration\Resources\Super;

class FullLibraryResourceTest extends ResourceTestCase
{
    protected $dumpsQueryCount = false;

    public function resourceProvider(): array
    {
        return [
            [ Library::class, 'full_library', Basic\FullLibrary\LibraryResource::class, Super\FullLibrary\LibraryResource::class ],
            [ Library::class, 'full_library', Basic\FullLibrary\LibraryResource::class, Super\FullLibrary\Preload\LibraryResource::class ],
            [ Library::class, 'full_library', Basic\FullLibrary\LibraryResource::class, Super\FullLibrary\Inline\LibraryResource::class ],
            [ Library::class, 'full_library', Basic\FullLibrary\LibraryResource::class, Super\FullLibrary\Callback\LibraryResource::class ],
            [ Library::class, 'full_library', Basic\FullLibrary\LibraryResource::class, Super\FullLibrary\Mix\LibraryResource::class ],
        ];
    }
}
