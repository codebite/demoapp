<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 19.02.2017
 * Time: 19:35
 */

namespace AppBundle\Benchmark;

/**
 * Class BenchmarkedUrlResult for creating benchmarked url result object holding extended properties
 * @package AppBundle\Benchmark
 */
class BenchmarkedUrlResult extends UrlResult
{
    /**
     * BenchmarkedUrlResult properties
     * @var array
     */
    protected $_data = array(
        'url' => null,
        'responseTime' => null,
        'isSlowerThanCompetitors' => null,
        'isTwiceSlowerThanCompetitors' => null,
    );
}