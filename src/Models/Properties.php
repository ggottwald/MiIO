<?php

namespace MiIO\Models;

/**
 * Class Properties
 * @package MiIO\Models
 */
class Properties implements \JsonSerializable
{
    private $properties = [];

    public function __construct($properties = [])
    {
        $this->fill($properties);
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param array $properties
     */
    public function fill($properties)
    {
        $this->properties = $properties;
    }

    /**
     * @param string $name
     * @return null|string
     */
    public function __get($name)
    {
        return $this->getProperty($name);
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function __set($name, $value)
    {
        $this->properties[$name] = $value;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return !is_null($this->getProperty($name));
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getProperty($key)
    {
        if (isset($this->properties[$key])) {
            return $this->properties[$key];
        }

        return null;
    }

    public function jsonSerialize()
    {
        return $this->properties;
    }
}