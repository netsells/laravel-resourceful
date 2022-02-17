<?php

namespace Netsells\Http\Resources;

use Netsells\Http\Resources\Json\JsonResource;
use Netsells\Http\Resources\Json\ResourceCollection;

class ChildResourceHandler
{
    public JsonResource|ResourceCollection $childResource;

    /** @var callable */
    public $resolver;

    public function __construct(JsonResource|ResourceCollection $childResource, callable $resolver)
    {
        $this->childResource = $childResource;
        $this->resolver = $resolver;
    }
}
