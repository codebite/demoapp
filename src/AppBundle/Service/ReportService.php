<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 21.02.2017
 * Time: 23:07
 */

namespace AppBundle\Service;

use AppBundle\Benchmark\BenchmarkResult;
use AppBundle\Benchmark\ReportManager;

class ReportService
{
    protected $reportType;
    protected $reportPath;

    public function __construct($reportType = ['txt'], $reportPath)
    {
        $this->reportType = $reportType;
        $this->reportPath = $reportPath;
    }

    public function createReport(BenchmarkResult $benchmarkResult)
    {
        $reportManager = new ReportManager($benchmarkResult);
        foreach ($this->reportType as $type){
            $reportBuilder = $reportManager->getReportBuilder($type, $this->getReportPath());
            $reportBuilder->createReport();
        }
    }

    /**
     * @return mixed
     */
    public function getReportPath()
    {
        return $this->reportPath;
    }

}