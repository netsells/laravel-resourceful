<?php

namespace Netsells\Http\Resources\Eloquent;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class Load extends EloquentDeferredValue
{
    /**
     * {@inheritDoc}
     */
    protected static function loadEloquentRelations(array $deferredValues): void
    {
        Collection::make($deferredValues)->groupBy('relations')
            ->each(function (Collection $collection, $relation) {
                EloquentCollection::make($collection->pluck('resource.resource'))
                    ->load($relation);
            });
    }
}
