<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 19.02.2017
 * Time: 20:03
 */

namespace AppBundle\Benchmark;

/**
 * Class CompetitorsUrlResults for iterating competitors results objects
 * @package AppBundle\Benchmark
 */
class CompetitorsUrlResults implements \Iterator, \Countable
{
    /**
     * Number of competitors object
     * @var int
     */
    private $count;

    /**
     * @var int
     */
    private $position = 0;

    /**
     * Array of results objects
     * @var array
     */
    protected $resultSet;

    /**
     * CompetitorsUrlResults constructor.
     * @param $results array of competitors results
     */
    public function __construct($results)
    {
        $this->resultSet = $results;
    }

    /**
     * Rewind the Iterator to the first element
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * Return the current element
     * @return CompetitorUrlResult|mixed
     */
    public function current()
    {
        $result = $this->resultSet[$this->position];
        if (!$result instanceof CompetitorUrlResult) {
            $result  = new CompetitorUrlResult($result);
            $this->resultSet[$this->position] = $result;
        }
        return $result;
    }

    /**
     * Return the key of the current element
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Move forward to next element
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * Checks if current position is valid
     * @return bool
     */
    public function valid()
    {
        return isset($this->resultSet[$this->key()]);
    }

    /**
     * Number of the elements we walk throw
     * @return int
     */
    public function count()
    {
        if (null === $this->count) {
            $this->count = count($this->resultSet);
        }
        return $this->count;
    }
}