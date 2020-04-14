<?php

namespace Netsells\Http\Resources\Eloquent;

trait ResolvesEloquentResources
{
    /**
     * @var string
     */
    protected $defaultDeferredValueClass = LoadMissing::class;

    /**
     * @param string|string[] $relations
     * @param callable|null $fn
     * @return EloquentDeferredValue
     */
    protected function load($relations, ?callable $fn = null): EloquentDeferredValue
    {
        return new Load($this, (array) $relations, $fn);
    }

    /**
     * @param string|string[] $relations
     * @param callable|null $fn
     * @return EloquentDeferredValue
     */
    protected function loadMissing($relations, ?callable $fn = null): EloquentDeferredValue
    {
        return new LoadMissing($this, (array) $relations, $fn);
    }

    /**
     * @param string|string[] $relations
     * @param callable|null $fn
     * @return EloquentDeferredValue
     */
    protected function loadCount($relations, ?callable $fn = null): EloquentDeferredValue
    {
        return new LoadCount($this, (array) $relations, $fn);
    }

    /**
     * @param string|string[] $relations
     * @param callable|null $fn
     * @return EloquentDeferredValue
     */
    protected function use($relations, ?callable $fn = null): EloquentDeferredValue
    {
        return new $this->defaultDeferredValueClass($this, (array) $relations, $fn);
    }

    /**
     * @param string ...$relations
     * @return EloquentDeferredValue
     */
    protected function preload(...$relations): EloquentDeferredValue
    {
        return new $this->defaultDeferredValueClass($this, $relations);
    }

    /**
     * @param string $relationship
     * @param string $resourceClass
     * @return EloquentDeferredValue
     */
    protected function many(string $relationship, string $resourceClass): EloquentDeferredValue
    {
        return $this->use($relationship, function ($model) use ($resourceClass) {
            return $resourceClass::collection($model);
        });
    }

    /**
     * @param string $relationship
     * @param string $resourceClass
     * @return EloquentDeferredValue
     */
    protected function one(string $relationship, string $resourceClass): EloquentDeferredValue
    {
        return $this->use($relationship, function ($model) use ($resourceClass) {
            return $resourceClass::make($model);
        });
    }
}
