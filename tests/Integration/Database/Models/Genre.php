<?php

namespace Netsells\Http\Resources\Tests\Integration\Database\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property bool $fiction
 * @property string $name
 * @property Collection|Book[] $books
 */
class Genre extends Model
{
    public $timestamps = false;

    protected $casts = [
        'fiction' => 'bool',
    ];

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class);
    }
}
