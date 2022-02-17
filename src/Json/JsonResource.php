<?php

namespace Netsells\Http\Resources\Json;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Netsells\Http\Resources\DeferredValue;
use Netsells\Http\Resources\Eloquent\ResolvesEloquentResources;
use Netsells\Http\Resources\ResolvesResources;

class JsonResource extends \Illuminate\Http\Resources\Json\JsonResource
{
    use ResolvesResources;
    use ResolvesEloquentResources;

    /**
     * @return DeferredValue|DeferredValue[]
     */
    public function preloads(/** @param Request $request */)
    {
        return [];
    }

    public static function collection(mixed $resource): AnonymousResourceCollection
    {
        return tap(new AnonymousResourceCollection($resource, static::class), function ($collection) {
            if (property_exists(static::class, 'preserveKeys')) {
                $collection->preserveKeys = (new static([]))->preserveKeys === true;
            }
        });
    }

    protected function beforeResolveRoot(Request $request): void
    {
        $this->collectAndResolvePreloads($request, Collection::make([$this]));
    }
}
