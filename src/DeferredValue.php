<?php

namespace Netsells\Http\Resources;

use Netsells\Http\Resources\Json\JsonResource;
use Netsells\Http\Resources\Json\ResourceCollection;

abstract class DeferredValue
{
    public JsonResource|ResourceCollection $resource;

    /** @var callable|null */
    public $callback;

    /** @var callable|null */
    public $resolver;

    public function __construct(JsonResource $resource, ?callable $callback = null)
    {
        $this->resource = $resource;
        $this->callback = $callback;
    }

    public function useResolver(callable $resolver): static
    {
        $this->resolver = $resolver;

        return $this;
    }

    /**
     * @param static[] $deferredValues
     */
    abstract public static function resolve(array $deferredValues): void;
}
