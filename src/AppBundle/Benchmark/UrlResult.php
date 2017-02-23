<?php
/**
 * Created by PhpStorm.
 * User: radek
 * Date: 19.02.2017
 * Time: 20:29
 */

namespace AppBundle\Benchmark;

/**
 * Class UrlResult Base class for holding information about url response time
 * @package AppBundle\Benchmark
 */
class UrlResult
{
    /**
     * Requested url params
     * @var array
     */
    protected $_data = array(
        'url' => null,
        'responseTime' => null,
    );

    /**
     * UrlResult constructor.
     * @param array $data array with url respond params
     */
    public function __construct($data)
    {
        $this->populate($data);
    }

    /**
     * Method form populating object properties     *
     * @param array $data data to populate
     * @return UrlResult $this
     * @throws \Exception
     */
    public function populate($data)
    {
        if (!is_array($data)) {
            throw new \Exception('Initial data must be an array');
        }
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }

    /**
     * Magic method for property setting
     * is run when writing data to inaccessible properties.
     * @param string $name property name
     * @param mixed $value property value
     * @throws \Exception
     */
    public function __set($name, $value)
    {
        if (!array_key_exists($name, $this->_data)) {
            throw new \Exception('Invalid property "' . $name . '"');
        }
        $this->_data[$name] = $value;
    }

    /**
     * Magic method for getting properties
     * is utilized for reading data from inaccessible properties
     * @param string $name property name
     * @return mixed|null
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->_data)) {
            return $this->_data[$name];
        }
        return null;
    }

    /**
     * Method is triggered by calling isset() or empty() on inaccessible properties.
     * @param string $name property name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->_data[$name]);
    }

    /**
     * Method is invoked when unset() is used on inaccessible properties.
     * @param string $name
     */
    public function __unset($name)
    {
        if (isset($this->$name)) {
            $this->_data[$name] = null;
        }
    }
}