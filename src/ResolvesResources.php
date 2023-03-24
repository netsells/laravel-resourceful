<?php

namespace Netsells\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\PotentiallyMissing;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Netsells\Http\Resources\Json\JsonResource;
use Netsells\Http\Resources\Json\ResourceCollection;

trait ResolvesResources
{
    protected bool $resolvingRoot = false;

    /**
     * Create an HTTP response that represents the object.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function toResponse($request)
    {
        $this->resolvingRoot = true;

        return parent::toResponse($request);
    }

    /**
     * Resolve the resource to an array.
     *
     * @param Request|null $request
     * @return array
     */
    public function resolve($request = null)
    {
        if ($this->resolvingRoot) {
            if (method_exists($this, 'beforeResolveRoot')) {
                $this->beforeResolveRoot($request);
            }

            return $this->resolveRoot($request, parent::resolve($request));
        }

        return parent::resolve($request);
    }

    protected function resolveRoot(Request $request, array $initiallyResolved): array
    {
        do {
            $deferredValues = $this->collectDeferredValues($initiallyResolved);

            if (! empty($deferredValues)) {
                $this->resolveDeferredValues($deferredValues);
            }

            $childResourceHandlers = $this->collectChildResourceHandlers($initiallyResolved);

            if (! empty($childResourceHandlers)) {
                $this->resolveChildResources($request, $childResourceHandlers);
            }
        } while (! empty($deferredValues) || ! empty($childResourceHandlers));

        return $initiallyResolved;
    }

    /**
     * @return DeferredValue[]
     */
    protected function collectDeferredValues(array &$resource): array
    {
        $deferredValues = [];

        foreach ($resource as $k => &$v) {
            if (is_array($v)) {
                $deferredValues = array_merge($deferredValues, $this->collectDeferredValues($v));
            } elseif ($v instanceof DeferredValue) {
                $deferredValues[] = $v->useResolver(function () use (&$resource, $k, $v) {
                    if ($v->callback) {
                        $resource[$k] = ($v->callback)(...func_get_args());
                    }
                });
            }
        }

        return $deferredValues;
    }

    /**
     * @param DeferredValue[] $deferredValues
     */
    protected function resolveDeferredValues(array $deferredValues): void
    {
        collect($deferredValues)->groupBy(function (DeferredValue $deferredValue) {
            return $deferredValue::class;
        })->each(function (Collection $deferredValues, $deferredValueClass) {
            $deferredValueClass::resolve($deferredValues->all());
        });
    }

    /**
     * @return ChildResourceHandler[]
     */
    protected function collectChildResourceHandlers(array &$resource): array
    {
        $childResourceHandlers = [];

        foreach ($resource as $k => &$v) {
            if (is_array($v)) {
                $childResourceHandlers = array_merge($childResourceHandlers, $this->collectChildResourceHandlers($v));
            } elseif ($v instanceof JsonResource || $v instanceof ResourceCollection) {
                $childResourceHandlers[] = new ChildResourceHandler($v, function () use (&$resource, $k, $v) {
                    $resource[$k] = $v->resolve(...func_get_args());
                });
            } elseif ($v instanceof PotentiallyMissing && $v->isMissing()) {
                unset($resource[$k]);
            }
        }

        return $childResourceHandlers;
    }

    /**
     * @param ChildResourceHandler[] $childResourceHandlers
     */
    protected function resolveChildResources(Request $request, array $childResourceHandlers): void
    {
        $resources = collect($childResourceHandlers)
            ->flatMap(function (ChildResourceHandler $childResourceHandler) {
                $childResource = $childResourceHandler->childResource;

                if ($childResource instanceof ResourceCollection) {
                    return $childResource->collection;
                }

                return Collection::make([$childResource]);
            });

        $this->collectAndResolvePreloads($request, $resources);

        foreach ($childResourceHandlers as $childResourceHandler) {
            ($childResourceHandler->resolver)($request);
        }
    }

    /**
     * @param Collection<JsonResource> $resources
     * @return Collection<DeferredValue>
     */
    protected function collectAndResolvePreloads(Request $request, Collection $resources): Collection
    {
        $preloads = $resources->map(function (JsonResource $resource) use ($request) {
            return Arr::wrap($resource->preloads($request));
        })->flatten();

        if (! $preloads->isEmpty()) {
            $this->resolveDeferredValues($preloads->all());
        }

        return $preloads;
    }
}
