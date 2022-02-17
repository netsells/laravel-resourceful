<?php

namespace Netsells\Http\Resources\Eloquent;

use Netsells\Http\Resources\DeferredValue;
use Netsells\Http\Resources\Json\JsonResource;

trait ResolvesEloquentResources
{
    /**
     * @var class-string<DeferredValue>
     */
    protected string $defaultDeferredValueClass = LoadMissing::class;

    /**
     * @param string|string[] $relations
     * @param callable|null $fn
     * @return EloquentDeferredValue
     */
    protected function load(string|array $relations, ?callable $fn = null): EloquentDeferredValue
    {
        return new Load($this, (array) $relations, $fn);
    }

    /**
     * @param string|string[] $relations
     * @param callable|null $fn
     * @return EloquentDeferredValue
     */
    protected function loadMissing(string|array $relations, ?callable $fn = null): EloquentDeferredValue
    {
        return new LoadMissing($this, (array) $relations, $fn);
    }

    /**
     * @param string|string[] $relations
     * @param callable|null $fn
     * @return EloquentDeferredValue
     */
    protected function loadCount(string|array $relations, ?callable $fn = null): EloquentDeferredValue
    {
        return new LoadCount($this, (array) $relations, $fn);
    }

    /**
     * @param string|string[] $relations
     * @param callable|null $fn
     * @return EloquentDeferredValue
     */
    protected function use(string|array $relations, ?callable $fn = null): EloquentDeferredValue
    {
        return new $this->defaultDeferredValueClass($this, (array) $relations, $fn);
    }

    /**
     * @param string ...$relations
     * @return EloquentDeferredValue
     */
    protected function preload(string ...$relations): EloquentDeferredValue
    {
        return new $this->defaultDeferredValueClass($this, $relations);
    }

    /**
     * @param string $relation
     * @param class-string<JsonResource> $resourceClass
     * @return EloquentDeferredValue
     */
    protected function many(string $relation, string $resourceClass): EloquentDeferredValue
    {
        return $this->use($relation, function ($model) use ($resourceClass) {
            return $resourceClass::collection($model);
        });
    }

    /**
     * @param string $relation
     * @param class-string<JsonResource> $resourceClass
     * @return EloquentDeferredValue
     */
    protected function one(string $relation, string $resourceClass): EloquentDeferredValue
    {
        return $this->use($relation, function ($model) use ($resourceClass) {
            return $resourceClass::make($model);
        });
    }
}
