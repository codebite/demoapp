<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 19.02.2017
 * Time: 13:36
 */

namespace AppBundle\Benchmark;

/**
 * Class Benchmark responsible for results calculation
 * @package AppBundle\Benchmark
 */
class Benchmark
{
    /**
     * @var BenchmarkSet
     */
    private $benchmarkSet;

    /**
     * Table for storing benchmarked url results
     * @var array
     */
    protected $benchmarkedUrlData;

    /**
     * Table for storing competitors benchmark results
     * @var array
     */
    protected $competitorsData;

    /**
     * @var WebRequest
     */
    protected $webRequest;


    /**
     * Benchmark constructor.
     * @param BenchmarkSet $benchmarkSet object with form data
     */
    public function __construct(BenchmarkSet $benchmarkSet)
    {
        //creating web request object for later use
        $this->benchmarkSet = $benchmarkSet;
        $this->webRequest = new WebRequest();
    }

    /**
     * Runs benchmark tests
     * @return $this
     */
    public function run()
    {
        //calculating the competitors url results
        $this->competitorsData = $this->benchmarkCompetitors(
            $this->benchmarkSet->getCompetitorsWebsites()
        );

        //running test for the benchmarked url
        $this->benchmarkedUrlData = $this->benchmarkBaseUrl(
            $this->benchmarkSet->getBenchmarkedWebsite()
        );

        return $this;
    }

    /**
     * Creates and returns object of the BenchmarkResult class
     *
     * @return BenchmarkResult
     */
    public function createBenchmarkResult()
    {
        return new BenchmarkResult(
            new BenchmarkedUrlResult($this->benchmarkedUrlData),
            new CompetitorsUrlResults($this->competitorsData)
        );
    }

    /**
     * Checks response time for competitors urls
     * @param array $competitors
     * @return array results of the competitors urls
     */
    protected function benchmarkCompetitors(array $competitors)
    {
        $compResult = [];
        foreach ($competitors as $competitorUrl){
            $compResult[] = [
                'url' => $competitorUrl,
                'responseTime' => $this->benchmarkLoadTime($competitorUrl)
            ];
        }
        return $compResult;
    }

    /**
     * Returns benchmarked url response time and comparison results
     * Method must be called dafter competitors test/check
     *
     * @param $url benchmarked url
     * @return array benchmarked url results
     * @throws \Exception
     */
    protected function benchmarkBaseUrl($url)
    {
        if(empty($this->competitorsData)){
            throw new \Exception('Benchmark for competitors should run before benchmarked url!');
        }
        $responseTime = $this->benchmarkLoadTime($url);

        return [
            'url' => $url,
            'responseTime' => $responseTime,
            'isSlowerThanCompetitors' => $this->isResponseSlowerThanCompetitors($responseTime, $this->competitorsData),
            'isTwiceSlowerThanCompetitors' => $this->isTwiceSlowerThanCompetitors($responseTime, $this->competitorsData),
        ];
    }

    /**
     * Compares benchmarked url response time against competitors result
     *
     * @param float $comparedTime benchmarked url response time
     * @param array $competitorsResult competitors url response time
     * @return bool true if benchmarked url responds slower than competitors
     */
    protected function isResponseSlowerThanCompetitors($comparedTime, array $competitorsResult)
    {
        foreach($competitorsResult as $compResult){
            if(($compResult['responseTime'] != 0) && ($comparedTime > $compResult['responseTime'])){
                return true;
            }
        }
        return false;
    }

    /**
     * Compares benchmarked url response time against competitors result
     *
     * @param float $comparedTime benchmarked url response time
     * @param array $competitorsResult competitors url response time
     * @return bool true if benchmarked url is twice slower than competitors
     */
    protected function isTwiceSlowerThanCompetitors($comparedTime, array $competitorsResult)
    {
        foreach($competitorsResult as $compResult){
            if(($compResult['responseTime'] != 0) && ($comparedTime > 2 * $compResult['responseTime'])){
                return true;
            }
        }
        return false;
    }

    /**
     * Gets the website load/response time in milliseconds
     *
     * @param string $url url to response check
     * @return float
     */
    protected function benchmarkLoadTime($url)
    {
        $this->webRequest->setUrl($url);

        $start = microtime(true);
        $result = $this->webRequest->run();
        $loadTime = microtime(true) - $start;

        if($result === false){
            return 0.0;
        }
        //multiplication by 1000 gives us milliseconds
        return $loadTime*1000;
    }

    /**
     * @return BenchmarkSet
     */
    public function getBenchmarkSet()
    {
        return $this->benchmarkSet;
    }

}