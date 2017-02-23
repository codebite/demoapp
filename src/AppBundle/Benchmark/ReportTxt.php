<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 22.02.2017
 * Time: 00:54
 */

namespace AppBundle\Benchmark;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class ReportTxt class for creating text log file with benchmark results
 * @package AppBundle\Benchmark
 */
class ReportTxt implements ReportInterface
{
    /**
     * @var string path where the benchmark logs are hold in
     */
    protected $path;

    /**
     * @var string name of the txt log file
     */
    protected $logFileName = 'benchmark.txt';

    /**
     * @var BenchmarkResult object holds results objects of the benchmark
     */
    protected $benchmarkResult;

    /**
     * ReportTxt constructor.
     * @param BenchmarkResult $benchmarkResult object holds results objects of the benchmark
     * @param string $path path to log directory
     */
    public function __construct(BenchmarkResult $benchmarkResult, $path)
    {
        $this->path = $path;
        $this->benchmarkResult = $benchmarkResult;
    }

    /**
     * Method responsible for creating benchmark log.
     * Creates log file and directory if they do not exist
     */
    public function createReport()
    {
        $this->createDir();
        $file = $this->getPath() . DIRECTORY_SEPARATOR . $this->getLogFileName();

        //writes to log file, if file not exists it will be created
        if(false === file_put_contents($file, $this->crateReportInput(), FILE_APPEND | LOCK_EX)){
            throw new IOException(sprintf('Failed to write results to "%s" because source file could not be opened for writing.', $file), 0, null, $file);
        }
    }

    /**
     * @return string Single line of data to save in the report
     */
    private function crateReportInput()
    {
        $benchmarkedUrlResult = $this->benchmarkResult->getBenchmarkedUrlResult();
        $competitorsResults = $this->benchmarkResult->getCompetitorsResults();

        $date = date('Y-m-d H:i:s');
        $content = sprintf("%s %s;%s;%s;", $date, $benchmarkedUrlResult->url, $benchmarkedUrlResult->responseTime, '');

        foreach ($competitorsResults as $competitor){
            $content .= sprintf(" %s;%s;%s", $competitor->url, $competitor->responseTime, $competitor->calculateDifference($benchmarkedUrlResult->responseTime));
        }
        $content .= "\n";

        return $content;
    }

    /**
     * Creates benchamrk log directory if not exists
     * @throws \Exception
     */
    private function createDir()
    {
        $fs = new Filesystem();
        try {
            $fs->mkdir($this->getPath());
        } catch (IOExceptionInterface $e) {
            throw  new \Exception("An error occurred while creating your directory at ".$e->getPath());
        }
    }

    /**
     * @return string path to log directory
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path stes path to log directory
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Returns log file name
     * @return string
     */
    public function getLogFileName()
    {
        return $this->logFileName;
    }

    /**
     * Sets log file name
     * @param string $logFileName
     */
    public function setLogFileName($logFileName)
    {
        $this->logFileName = $logFileName;
    }


}