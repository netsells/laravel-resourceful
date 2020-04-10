<?php

namespace Netsells\Http\Resources\Tests\Integration;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Book;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Library;
use Netsells\Http\Resources\Tests\Integration\Resources\Basic;
use Netsells\Http\Resources\Tests\Integration\Resources\Super;

class RelatedResourceTest extends ResourceTestCase
{
    protected $dumpsQueryCount = true;

    public function resourceProvider(): array
    {
        return [
            [ Book::class, ['simple', 'coauthors', 'related_books'], Basic\Related\BookResource::class, Super\Related\BookResource::class ],
        ];
    }

    /**
     * @param string $modelClass
     * @param int $amount
     * @param array $states
     * @return Collection|Model
     */
    protected function produce(string $modelClass, int $amount, array $states)
    {
        /** @var Library $library */
        $library = factory(Library::class)->states($states)->create();

        if ($amount > 1) {
            return $library->books()->limit($amount)->get();
        }

        return $library->books()->first();
    }
}
