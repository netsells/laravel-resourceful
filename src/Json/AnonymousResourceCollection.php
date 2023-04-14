<?php

namespace Netsells\Http\Resources\Json;

class AnonymousResourceCollection extends ResourceCollection
{
    /**
     * Indicates if the collection keys should be preserved.
     *
     * @var bool
     */
    public $preserveKeys = false;

    public function __construct(mixed $resource, string $collects)
    {
        $this->collects = $collects;

        parent::__construct($resource);
    }
}
