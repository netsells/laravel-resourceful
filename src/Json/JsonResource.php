<?php

namespace Netsells\Http\Resources\Json;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Netsells\Http\Resources\Eloquent\ResolvesEloquentResources;
use Netsells\Http\Resources\ResolvesResources;

class JsonResource extends \Illuminate\Http\Resources\Json\JsonResource
{
    use ResolvesResources;
    use ResolvesEloquentResources;

    /**
     * @deprecated This method can be removed when dropping support for Laravel 9.
     */
    public static function collection($resource)
    {
        return tap(static::newCollection($resource), function ($collection) { // @phpstan-ignore-line
            if (property_exists(static::class, 'preserveKeys')) {
                $collection->preserveKeys = (new static([]))->preserveKeys === true; // @phpstan-ignore-line
            }
        });
    }

    protected function beforeResolveRoot(Request $request): void
    {
        $this->collectAndResolvePreloads($request, Collection::make([$this]));
    }

    protected static function newCollection(mixed $resource): AnonymousResourceCollection
    {
        return new AnonymousResourceCollection($resource, static::class);
    }
}
