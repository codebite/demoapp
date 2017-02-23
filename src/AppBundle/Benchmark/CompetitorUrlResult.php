<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 19.02.2017
 * Time: 20:19
 */

namespace AppBundle\Benchmark;

/**
 * Class CompetitorUrlResult
 * @package AppBundle\Benchmark
 */
class CompetitorUrlResult extends UrlResult
{
    /**
     * @param float $benchmarkedLoadTime benchmarked url response time
     * @return float time difference between benchmarked url and competitor respons time
     */
    public function calculateDifference($benchmarkedLoadTime)
    {
        return $benchmarkedLoadTime - $this->responseTime;
    }

}