<?php

namespace Netsells\Http\Resources\Json;

use Netsells\Http\Resources\ResolvesResources;

class ResourceCollection extends \Illuminate\Http\Resources\Json\ResourceCollection
{
    use ResolvesResources;

    protected function beforeResolveRoot($request)
    {
        $this->collectAndResolvePreloads($request, $this->collection);
    }
}
