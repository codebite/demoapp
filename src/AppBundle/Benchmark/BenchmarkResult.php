<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 18.02.2017
 * Time: 23:15
 */

namespace AppBundle\Benchmark;

/**
 * Class BenchmarkResult
 * @package AppBundle\Benchmark
 */
class BenchmarkResult
{

    /**
     * Result object fot the benchmarked
     * @var BenchmarkedUrlResult
     */
    protected $benchmarkedUrlResult;

    /**
     * Iterator object with contains Results for the competitors
     * @var CompetitorsUrlResults
     */
    protected $competitorsResults;

    /**
     * BenchmarkResult constructor.
     * @param BenchmarkedUrlResult $benchmarkedUrlResult
     * @param CompetitorsUrlResults $competitorsResult
     */
    public function __construct(BenchmarkedUrlResult $benchmarkedUrlResult, CompetitorsUrlResults $competitorsResult)
    {
        $this->benchmarkedUrlResult = $benchmarkedUrlResult;
        $this->competitorsResults = $competitorsResult;
    }

    /**
     * @return BenchmarkedUrlResult
     */
    public function getBenchmarkedUrlResult()
    {
        return $this->benchmarkedUrlResult;
    }

    /**
     * @return CompetitorsUrlResults
     */
    public function getCompetitorsResults()
    {
        return $this->competitorsResults;
    }

}