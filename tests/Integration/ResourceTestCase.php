<?php

namespace Netsells\Http\Resources\Tests\Integration;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ResourceTestCase extends TestCase
{
    protected $dumpsQueryCount = false;

    /**
     * @dataProvider resourceProvider
     * @param string $modelClass
     * @param array $states
     * @param string $basicClass
     * @param string $superClass
     */
    public function testSuperMatchesBasicResource(string $modelClass, array $states, string $basicClass, string $superClass)
    {
        /** @var Model $model */
        $model = factory($modelClass)->states($states)->create();

        $basicResource = $basicClass::make($model)->response()->content();
        $superResource = $superClass::make($model)->response()->content();

        $this->assertJsonStringEqualsJsonString($basicResource, $superResource);
    }

    /**
     * @dataProvider resourceProvider
     * @param string $modelClass
     * @param array $states
     * @param string $basicClass
     * @param string $superClass
     */
    public function testSuperMatchesBasicResourceCollection(string $modelClass, array $states, string $basicClass, string $superClass)
    {
        /** @var Collection $models */
        $models = factory($modelClass, 2)->states($states)->create();

        $basicResource = $basicClass::collection($models)->response()->content();
        $superResource = $superClass::collection($models)->response()->content();

        $this->assertJsonStringEqualsJsonString($basicResource, $superResource);
    }

    /**
     * @dataProvider resourceProvider
     * @param string $modelClass
     * @param array $states
     * @param string $basicClass
     * @param string $superClass
     */
    public function testSuperReducesQueriesOverBasicResource(string $modelClass, array $states, string $basicClass, string $superClass)
    {
        /** @var Model $model */
        $model = factory($modelClass)->states($states)->create();
        $this->countingQueries($basicQueryCount, function () use ($basicClass, $model) {
            return $basicClass::make($model)->response()->content();
        });

        $model = $model->fresh();
        $this->countingQueries($superQueryCount, function () use ($superClass, $model) {
            return $superClass::make($model)->response()->content();
        });

        if ($this->dumpsQueryCount) {
            dump($basicQueryCount . ' >= ' . $superQueryCount);
        }
        $this->assertLessThanOrEqual($basicQueryCount, $superQueryCount);
    }

    /**
     * @dataProvider resourceProvider
     * @param string $modelClass
     * @param array $states
     * @param string $basicClass
     * @param string $superClass
     */
    public function testSuperReducesQueriesOverBasicResourceCollection(string $modelClass, array $states, string $basicClass, string $superClass)
    {
        /** @var Collection $models */
        $models = factory($modelClass, 2)->states($states)->create();
        $this->countingQueries($basicQueryCount, function () use ($basicClass, $models) {
            return $basicClass::collection($models)->response()->content();
        });

        $models = $models->fresh();
        $this->countingQueries($superQueryCount, function () use ($superClass, $models) {
            return $superClass::collection($models)->response()->content();
        });

        if ($this->dumpsQueryCount) {
            dump($basicQueryCount . ' >= ' . $superQueryCount);
        }
        $this->assertLessThanOrEqual($basicQueryCount, $superQueryCount);
    }
}
