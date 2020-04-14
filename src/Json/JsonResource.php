<?php

namespace Netsells\Http\Resources\Json;

use Illuminate\Support\Collection;
use Netsells\Http\Resources\DeferredValue;
use Netsells\Http\Resources\LoadMissingDeferredValue;
use Netsells\Http\Resources\ResolvesResources;

class JsonResource extends \Illuminate\Http\Resources\Json\JsonResource
{
    use ResolvesResources;

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

    /**
     * @param string|string[] $relations
     * @param callable|null $fn
     * @return DeferredValue
     */
    protected function use($relations, ?callable $fn = null): DeferredValue
    {
        return new LoadMissingDeferredValue($this, (array) $relations, $fn);
    }

    /**
     * @param string ...$relations
     * @return DeferredValue
     */
    protected function preload(...$relations): DeferredValue
    {
        return new LoadMissingDeferredValue($this, $relations);
    }

    /**
     * @param string $relationship
     * @param string $resourceClass
     * @return DeferredValue
     */
    protected function many(string $relationship, string $resourceClass): DeferredValue
    {
        return $this->use($relationship, function ($model) use ($resourceClass) {
            return $resourceClass::collection($model);
        });
    }

    /**
     * @param string $relationship
     * @param string $resourceClass
     * @return DeferredValue
     */
    protected function one(string $relationship, string $resourceClass): DeferredValue
    {
        return $this->use($relationship, function ($model) use ($resourceClass) {
            return $resourceClass::make($model);
        });
    }

    protected function beforeResolveRoot($request)
    {
        $this->collectAndResolvePreloads($request, Collection::make([$this]));
    }
}
