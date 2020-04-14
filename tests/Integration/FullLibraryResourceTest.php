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
            [ Basic\FullLibrary\LibraryResource::class, Super\FullLibrary\LibraryResource::class ],
            [ Basic\FullLibrary\LibraryResource::class, Super\FullLibrary\Preload\LibraryResource::class ],
            [ Basic\FullLibrary\LibraryResource::class, Super\FullLibrary\Inline\LibraryResource::class ],
            [ Basic\FullLibrary\LibraryResource::class, Super\FullLibrary\Callback\LibraryResource::class ],
            [ Basic\FullLibrary\LibraryResource::class, Super\FullLibrary\Mix\LibraryResource::class ],
        ];
    }

    /**
     * @inheritDoc
     */
    protected function produce(int $amount)
    {
        return factory(Library::class, $amount > 1 ? $amount : null)
            ->preset('full_library')
            ->create();
    }
}
