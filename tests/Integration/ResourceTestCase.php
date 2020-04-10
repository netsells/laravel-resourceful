<?php

namespace Netsells\Http\Resources\Tests\Integration;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ResourceTestCase extends TestCase
{
    protected $dumpsQueryCount = false;
    protected $collectionSize = 4;

    /**
     * @dataProvider resourceProvider
     * @param string $modelClass
     * @param string $preset
     * @param string $basicClass
     * @param string $superClass
     */
    public function testSuperReducesQueriesOverBasicResourceAndBothMatch(string $modelClass, string $preset, string $basicClass, string $superClass)
    {
        /** @var Model $model */
        $model = $this->produce($modelClass, 1, $preset)->fresh();
        $basicResource = $this->withQueryLog($basicQueryLog, function () use ($basicClass, $model) {
            return $basicClass::make($model)->response()->content();
        });
        $basicQueryCount = count($basicQueryLog);

        $model = $model->fresh();
        $superResource = $this->withQueryLog($superQueryLog, function () use ($superClass, $model) {
            return $superClass::make($model)->response()->content();
        });
        $superQueryCount = count($superQueryLog);

        if ($this->dumpsQueryCount) {
            if ($basicQueryCount < $superQueryCount) {
                dump([
                    'json' => $basicResource,
                    'basic' => $basicQueryLog,
                    'super' => $superQueryLog,
                ]);
            } else {
                dump($basicQueryCount . ' >= ' . $superQueryCount);
            }
        }

        $this->assertJsonStringEqualsJsonString($basicResource, $superResource);
        $this->assertLessThanOrEqual($basicQueryCount, $superQueryCount);
    }

    /**
     * @dataProvider resourceProvider
     * @param string $modelClass
     * @param string $preset
     * @param string $basicClass
     * @param string $superClass
     */
    public function testSuperReducesQueriesOverBasicResourceCollectionAndBothMatch(string $modelClass, string $preset, string $basicClass, string $superClass)
    {
        /** @var Collection $models */
        $models = $this->produce($modelClass, $this->collectionSize, $preset)->fresh();
        $basicResource = $this->withQueryLog($basicQueryLog, function () use ($basicClass, $models) {
            return $basicClass::collection($models)->response()->content();
        });
        $basicQueryCount = count($basicQueryLog);

        $models = $models->fresh();
        $superResource = $this->withQueryLog($superQueryLog, function () use ($superClass, $models) {
            return $superClass::collection($models)->response()->content();
        });
        $superQueryCount = count($superQueryLog);

        if ($this->dumpsQueryCount) {
            if ($basicQueryCount < $superQueryCount) {
                dump([
                    'json' => $basicResource,
                    'basic' => $basicQueryLog,
                    'super' => $superQueryLog,
                ]);
            } else {
                dump($basicQueryCount . ' >= ' . $superQueryCount);
            }
        }

        $this->assertJsonStringEqualsJsonString($basicResource, $superResource);
        $this->assertLessThanOrEqual($basicQueryCount, $superQueryCount);
    }

    /**
     * @param string $modelClass
     * @param int $amount
     * @param string $preset
     * @return Collection|Model
     */
    protected function produce(string $modelClass, int $amount, string $preset)
    {
        return factory($modelClass, $amount > 1 ? $amount : null)->preset($preset)->create();
    }
}
