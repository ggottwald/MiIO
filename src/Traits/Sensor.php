<?php

namespace MiIO\Traits;

trait Sensor
{
    /**
     * @return float
     */
    public function getTemperature()
    {
        return $this->getProperties()->temp_dec / 10;
    }

    /**
     * @return int
     */
    public function getHumidity()
    {
        return (int)$this->getProperties()->humidity;
    }
}