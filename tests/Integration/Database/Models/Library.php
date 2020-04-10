<?php

namespace Netsells\Http\Resources\Tests\Integration\Database\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * @property int $id
 * @property string $name
 * @property string $city
 * @property Collection|Shelf[] $shelves
 * @property Collection|Book[] $books
 */
class Library extends Model
{
    public $timestamps = false;

    public function shelves(): HasMany
    {
        return $this->hasMany(Shelf::class);
    }

    public function books(): HasManyThrough
    {
        return $this->hasManyThrough(Book::class, Shelf::class, 'library_id', 'shelf_id');
    }
}
