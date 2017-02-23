<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 19.02.2017
 * Time: 12:08
 */

namespace AppBundle\Benchmark;

/**
 * WebRequest Class for getting the benchmarked url website content
 *
 * @package AppBundle\Benchmark
 */
class WebRequest
{
    /**
     * Website url
     * @var string
     */
    protected $url;

    /**
     * Variable holds response returned by host at given url
     * @var mixed
     */
    protected $response;

    /**
     * WebRequest constructor.
     * @param string $url Website url we want to get
     */
    public function __construct($url = '')
    {
        $this->url = $url;
    }

    /**
     * Method gets the content from the website url
     *
     * @return mixed|string
     * @throws \Exception
     */
    public function run()
    {
        if(empty($this->url)){
            throw new \Exception('Wrong request param, expected value should be valid url!');
        }
        $this->response = file_get_contents($this->url);
        return $this->response;
    }

    /**
     * Method returns website url that we are sending request to
     * @return string website url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Methos sets the url
     * @param string $url website url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

}