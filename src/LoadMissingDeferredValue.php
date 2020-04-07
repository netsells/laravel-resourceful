<?php

namespace Netsells\Http\Resources;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Netsells\Http\Resources\Json\JsonResource;
use Netsells\Http\Resources\Json\ResourceCollection;

class LoadMissingDeferredValue extends DeferredValue
{
    /** @var string[] */
    public $relations;

    /**
     * LoadMissingDeferredValue constructor.
     * @param JsonResource|ResourceCollection $resource
     * @param array $relations
     * @param callable|null $callback
     */
    public function __construct($resource, array $relations, ?callable $callback = null)
    {
        parent::__construct($resource, $callback);
        $this->relations = $relations;
    }

    /**
     * @param static[] $deferredValues
     */
    static function resolve(array $deferredValues)
    {
        collect($deferredValues)->groupBy('relations')
            ->each(function (Collection $collection, $relation) {
                EloquentCollection::make($collection->pluck('resource.resource'))
                    ->loadMissing($relation);

                $collection->each(function (LoadMissingDeferredValue $deferredValue) use ($relation) {
                    if ($deferredValue->resolver) {
                        ($deferredValue->resolver)($deferredValue->resource->$relation);
                    }
                });
            });
    }
}
