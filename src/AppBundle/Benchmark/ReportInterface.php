<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 18.02.2017
 * Time: 23:22
 */

namespace AppBundle\Benchmark;

/**
 * Interface ReportInterface
 * @package AppBundle\Benchmark
 */
interface ReportInterface
{
    /**
     * ReportInterface constructor.
     * @param BenchmarkResult $benchmarkResult
     * @param $path
     */
    public function __construct(BenchmarkResult $benchmarkResult, $path);

    /**
     * Method responsible for creating report
     * @return mixed
     */
    public function createReport();
}