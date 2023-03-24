<?php

namespace Netsells\Http\Resources\Tests\Integration;

use Illuminate\Support\Collection;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Author;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Book;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Library;
use Netsells\Http\Resources\Tests\Integration\Database\Models\Shelf;
use Netsells\Http\Resources\Tests\Integration\Resources\Basic;
use Netsells\Http\Resources\Tests\Integration\Resources\Super;

class BooksIndexResourceTest extends ResourceTestCase
{
    protected bool $dumpsQueryCount = false;

    protected bool $dumpsJson = false;

    public static function resourceProvider(): array
    {
        return [
            [Basic\BooksIndex\BookResource::class, Super\BooksIndex\BookResource::class],
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function produce(int $amount)
    {
        /** @var Book|Collection<Book> $books */
        $books = Book::factory()->count($amount > 1 ? $amount : null)->forAuthor()->create();

        $this->assignAuthors();
        $this->assignCoauthors();
        $this->assignRelatedBooks();

        return $books;
    }

    protected function assignAuthors(): void
    {
        Book::query()->each(function (Book $book) {
            if ($this->faker->boolean(60)) {
                return;
            }

            $shelf = Shelf::factory()
                ->for(Library::factory())
                ->has(
                    Book::factory()
                        ->for(Author::factory())
                        ->count($this->faker->numberBetween(1, 3)),
                );

            $book->shelf()->associate($shelf->create())->save();
        });
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
