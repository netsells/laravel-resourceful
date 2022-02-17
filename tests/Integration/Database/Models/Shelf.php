<?php

namespace Netsells\Http\Resources\Tests\Integration\Database\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Netsells\Http\Resources\Tests\Integration\Database\Factories\ShelfFactory;

/**
 * @property int $id
 * @property int $library_id
 * @property string $row
 * @property string $column
 * @property Library $library
 * @property Collection|Book[] $books
 * @property Collection|Author[] $authors
 */
class Shelf extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function library(): BelongsTo
    {
        return $this->belongsTo(Library::class);
    }

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    public function authors(): HasManyThrough
    {
        return $this->hasManyThrough(Author::class, Book::class, 'shelf_id', 'id', null, 'author_id');
    }

    protected static function newFactory(): ShelfFactory
    {
        return ShelfFactory::new();
    }
}
