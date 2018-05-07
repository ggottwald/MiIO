<?php

namespace MiIO\Contracts;

use React\Promise\Promise;

interface ColorTemperatureContract
{
    /**
     * Get current color temperature
     *
     * @return string|null
     */
    public function getColorTemperature();

    /**
     * Set color temperature
     *
     * @param int|string $value
     * @return Promise
     */
    public function setColorTemperature($value);
}