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

    protected function beforeResolveRoot(Request $request): void
    {
        $this->collectAndResolvePreloads($request, Collection::make([$this]));
    }

    protected static function newCollection(mixed $resource): AnonymousResourceCollection
    {
        return new AnonymousResourceCollection($resource, static::class);
    }
}
