<?php

namespace Netsells\Http\Resources\Tests\Integration;

use Faker\Generator as Faker;
use Makeable\LaravelFactory\FactoryBuilder;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Author;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Book;
use Netsells\Http\Resources\Tests\Integration\Resources\Basic;
use Netsells\Http\Resources\Tests\Integration\Resources\Super;

class BooksIndexResourceTest extends ResourceTestCase
{
    protected $dumpsQueryCount = false;
    protected $dumpsJson = false;

    public function resourceProvider(): array
    {
        return [
            [ Basic\BooksIndex\BookResource::class, Super\BooksIndex\BookResource::class ],
        ];
    }

    /**
     * @inheritDoc
     */
    protected function produce(int $amount)
    {
        return tap(
            factory(Book::class, $amount > 1 ? $amount : null)
                ->with('author')
                ->odds('40%', function (FactoryBuilder $book) {
                    return $book->with('shelf', function (FactoryBuilder $shelf, Faker $faker) {
                        return $shelf->with('library')
                            ->with($faker->numberBetween(1, 3), 'books')
                            ->with(1, 'books.author');
                    });
                })
                ->create(),
            function () {
                $this->assignCoauthors();
                $this->assignRelatedBooks();
            }
        );
    }

    protected function assignCoauthors(): void
    {
        Book::query()->each(function (Book $book) {
            $otherAuthors = Author::query()
                ->inRandomOrder()
                ->limit($this->faker->numberBetween(1, 3))
                ->where('id', '!=', $book->author_id)
                ->get();

            $book->coauthors()->saveMany($otherAuthors);
        });
    }

    protected function assignRelatedBooks(): void
    {
        Book::query()->each(function (Book $book) {
            $alreadyExistsInRelatedBooks = Book::query()->from('related_books')
                ->where('book_id', $book->id)
                ->orWhere('related_book_id', $book->id)
                ->exists();

            if ($alreadyExistsInRelatedBooks) {
                return;
            }

            $book->relatedBooks()->saveMany(
                Book::query()
                    ->inRandomOrder()
                    ->limit($this->faker->numberBetween(1, 3))
                    ->where('id', '!=', $book->id)
                    ->get()
            );
        });
    }
}
