<?php

namespace MiIO\Traits;

use MiIO\Models\Properties;
use Socket\Raw\Exception;

trait Sensor
{
    /**
     * @return float
     * @throws Exception
     */
    public function getTemperature()
    {
        $properties = $this->getProperties();

        return $properties instanceof Properties
            ? ($properties->temp_dec / 10)
            : null;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function getHumidity()
    {
        $properties = $this->getProperties();

        return $properties instanceof Properties
            ? ((int)$properties->humidity)
            : null;
    }
}