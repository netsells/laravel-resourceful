<?php

namespace Netsells\Http\Resources\Eloquent;

use Illuminate\Support\Collection;
use Netsells\Http\Resources\DeferredValue;
use Netsells\Http\Resources\Json\JsonResource;
use Netsells\Http\Resources\Json\ResourceCollection;

abstract class EloquentDeferredValue extends DeferredValue
{
    /**
     * @var string[]
     */
    public array $relations;

    public function __construct(JsonResource|ResourceCollection $resource, array $relations, ?callable $callback = null)
    {
        parent::__construct($resource, $callback);
        $this->relations = $relations;
    }

    /**
     * @param static[] $deferredValues
     */
    public static function resolve(array $deferredValues): void
    {
        static::loadEloquentRelations($deferredValues);

        Collection::make($deferredValues)
            ->filter(function (EloquentDeferredValue $deferredValue) {
                return $deferredValue->resolver;
            })
            ->each(function (EloquentDeferredValue $deferredValue) {
                $relations = Collection::make($deferredValue->relations)->map(function ($relation) use ($deferredValue) {
                    return data_get($deferredValue->resource, $relation);
                })->all();

                ($deferredValue->resolver)(...$relations);
            });
    }

    /**
     * Begins eager loading eloquent model relations.
     *
     * @param static[] $deferredValues
     */
    abstract protected static function loadEloquentRelations(array $deferredValues): void;
}
