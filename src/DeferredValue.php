<?php

namespace Netsells\Http\Resources;

use Netsells\Http\Resources\Json\JsonResource;
use Netsells\Http\Resources\Json\ResourceCollection;

abstract class DeferredValue
{
    /**
     * @var JsonResource|ResourceCollection
     */
    public $resource;

    /** @var callable|null */
    public $callback;

    /** @var callable|null */
    public $resolver;

    /**
     * DeferredValue constructor.
     * @param JsonResource $resource
     * @param callable|null $callback
     */
    public function __construct($resource, ?callable $callback = null)
    {
        $this->resource = $resource;
        $this->callback = $callback;
    }

    /**
     * @param callable $resolver
     * @return $this
     */
    public function useResolver(callable $resolver): self
    {
        $this->resolver = $resolver;

        return $this;
    }

    /**
     * @param static[] $deferredValues
     */
    abstract static function resolve(array $deferredValues);
}
