<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 22.02.2017
 * Time: 00:54
 */

namespace AppBundle\Benchmark;

/**
 * Class ReportHtml Specialized class for creating html type of benchmark log
 * @package AppBundle\Benchmark
 */
class ReportHtml implements ReportInterface
{
    /**
     * Path where the logs are hold in
     * @var string
     */
    protected $path;

    /**
     * Object holds results of the benchmark
     * @var BenchmarkResult
     */
    protected $benchmarkResult;

    /**
     * ReportHtml constructor.
     * @param BenchmarkResult $benchmarkResult
     * @param string $path path to logs directory
     */
    public function __construct(BenchmarkResult $benchmarkResult, $path)
    {
        $this->path = $path;
        $this->benchmarkResult = $benchmarkResult;
    }

    /**
     * Creates html report
     */
    public function createReport()
    {
        // implementation for creating html report
    }
}