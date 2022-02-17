<?php

namespace Netsells\Http\Resources\Json;

use Illuminate\Http\Request;
use Netsells\Http\Resources\ResolvesResources;

class ResourceCollection extends \Illuminate\Http\Resources\Json\ResourceCollection
{
    use ResolvesResources;

    protected function beforeResolveRoot(Request $request): void
    {
        $this->collectAndResolvePreloads($request, $this->collection);
    }
}
