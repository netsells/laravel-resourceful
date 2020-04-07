<?php

namespace Netsells\Http\Resources;

use Netsells\Http\Resources\Json\JsonResource;
use Netsells\Http\Resources\Json\ResourceCollection;

class ChildResourceHandler
{
    /** @var JsonResource|ResourceCollection */
    public $childResource;

    /** @var callable */
    public $resolver;

    /**
     * ChildResourceHandler constructor.
     * @param JsonResource|ResourceCollection $childResource
     * @param callable $resolver
     */
    public function __construct($childResource, callable $resolver)
    {
        $this->childResource = $childResource;
        $this->resolver = $resolver;
    }
}
