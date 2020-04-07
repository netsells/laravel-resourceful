<?php

namespace Netsells\Http\Resources\Json;

use Illuminate\Support\Arr;
use Netsells\Http\Resources\ResolvesResources;

class ResourceCollection extends \Illuminate\Http\Resources\Json\ResourceCollection
{
    use ResolvesResources;

    protected function beforeResolveRoot($request)
    {
        $deferredRelations = $this->collection->filter(function (JsonResource $resource) use ($request) {
            return method_exists($resource, 'deferredRelations');
        })->map(function (JsonResource $resource) use ($request) {
            return Arr::wrap($resource->deferredRelations($request));
        })->flatten();

        if ($deferredRelations->isEmpty()) {
            return;
        }

        $this->resolveDeferredValues(
            $deferredRelations->toArray()
        );
    }
}
