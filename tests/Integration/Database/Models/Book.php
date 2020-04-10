<?php

namespace Netsells\Http\Resources\Tests\Integration\Database\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $shelf_id
 * @property int $author_id
 * @property string $isbn
 * @property string $title
 * @property string $text
 * @property Shelf $shelf
 * @property Author $author
 * @property Collection|Author[] $coauthors
 * @property Collection|Genre[] $genres
 * @property Collection|Book[] $relatedBooks
 */
class Book extends Model
{
    public $timestamps = false;

    public function shelf(): BelongsTo
    {
        return $this->belongsTo(Shelf::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function coauthors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'book_coauthor', 'book_id', 'author_id');
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function relatedBooks(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'related_books');
    }
}
