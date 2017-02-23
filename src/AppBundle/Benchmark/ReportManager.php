<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 21.02.2017
 * Time: 23:05
 */

namespace AppBundle\Benchmark;

/**
 * Class ReportManager responsible for creating objects of the specialized report types classes
 * @package AppBundle\Benchmark
 */
class ReportManager
{
    /**
     * Log file types
     */
    const TYPE_TXT = 'txt';
    const TYPE_HTML = 'html';

    /**
     * Object with benchamrk results
     * @var BenchmarkResult
     */
    protected $benchmarkResult;

    /**
     * ReportManager constructor.
     * @param BenchmarkResult $benchmarkResult object with results
     */
    public function __construct(BenchmarkResult $benchmarkResult)
    {
        $this->benchmarkResult = $benchmarkResult;
    }

    /**
     * Factory method for creating specific report type object
     * @param string $reportType report type
     * @param string $reportPath path to benchmark log directory
     * @return ReportHtml|ReportTxt instance of the report class
     * @throws \Exception
     */
    public function getReportBuilder($reportType= 'txt', $reportPath) {
        switch ($reportType) {
            case (self::TYPE_TXT):
                return new ReportTxt($this->benchmarkResult, $reportPath);
            case (self::TYPE_HTML):
                return new ReportHtml($this->benchmarkResult, $reportPath);
            default :
                throw new \Exception($this->reportType . ' report type undefined!');
        }
    }

}