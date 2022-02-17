<?php

namespace Netsells\Http\Resources\Tests\Integration\Database\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Netsells\Http\Resources\Tests\Integration\Database\Factories\AuthorFactory;

/**
 * @property int $id
 * @property string $firstname
 * @property string $lastname
 * @property Collection|Book[] $books
 * @property Collection|Author[] $collaborators
 */
class Author extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    public function collaborators(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, Book::class, 'author_id', 'author_id')
            ->join('book_coauthor', 'book_coauthor.book_id', '=', 'books.id');
    }

    protected static function newFactory(): AuthorFactory
    {
        return AuthorFactory::new();
    }
}
