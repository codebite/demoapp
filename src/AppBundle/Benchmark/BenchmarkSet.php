<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 18.02.2017
 * Time: 18:57
 */

namespace AppBundle\Benchmark;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class BenchmarkSet
 * @package AppBundle\Benchmark
 */
class BenchmarkSet
{
    /**
     * @Assert\Url()
     */
    protected $benchmarkedWebsite;

    /**
     * @Assert\All({
     *     @Assert\NotBlank,
     *     @Assert\Url()
     * })
     */
    protected $competitorsWebsites = [];

    /**
     * @return string
     */
    public function getBenchmarkedWebsite()
    {
        return $this->benchmarkedWebsite;
    }

    /**
     * @param string $benchmarkedWebsite
     */
    public function setBenchmarkedWebsite($benchmarkedWebsite)
    {
        $this->benchmarkedWebsite = $benchmarkedWebsite;
    }

    /**
     * @return string
     */
    public function getCompetitorsWebsites()
    {
        return $this->competitorsWebsites;
    }

    /**
     * @param array $competitorsWebsites
     */
    public function setCompetitorsWebsites($competitorsWebsites)
    {
        $this->competitorsWebsites = $competitorsWebsites;
    }
}