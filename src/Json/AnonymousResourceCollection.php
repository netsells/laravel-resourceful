<?php

namespace Netsells\Http\Resources\Json;

class AnonymousResourceCollection extends ResourceCollection
{
    public function __construct(mixed $resource, string $collects)
    {
        $this->collects = $collects;

        parent::__construct($resource);
    }
}
