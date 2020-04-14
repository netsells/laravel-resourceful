<?php

namespace Netsells\Http\Resources\Json;

use Illuminate\Support\Collection;
use Netsells\Http\Resources\Eloquent\ResolvesEloquentResources;
use Netsells\Http\Resources\ResolvesResources;

class JsonResource extends \Illuminate\Http\Resources\Json\JsonResource
{
    use ResolvesResources, ResolvesEloquentResources;

    /**
     * Create new anonymous resource collection.
     *
     * @param  mixed  $resource
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public static function collection($resource)
    {
        return tap(new AnonymousResourceCollection($resource, static::class), function ($collection) {
            if (property_exists(static::class, 'preserveKeys')) {
                $collection->preserveKeys = (new static([]))->preserveKeys === true;
            }
        });
    }

    protected function beforeResolveRoot($request)
    {
        $this->collectAndResolvePreloads($request, Collection::make([$this]));
    }
}
