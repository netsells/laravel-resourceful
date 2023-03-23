<?php

namespace Netsells\Http\Resources\Tests\Integration;

use Netsells\Http\Resources\Tests\Integration\Resources\Basic;
use Netsells\Http\Resources\Tests\Integration\Resources\Super;

class FullLibraryResourceTest extends ResourceTestCase
{
    use Presets;

    protected bool $dumpsQueryCount = false;

    public static function resourceProvider(): array
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
        return $this->fullLibraryFactory($amount > 1 ? $amount : null)->create();
    }
}
