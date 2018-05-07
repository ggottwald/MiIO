<?php

namespace MiIO\Contracts;

use React\Promise\Promise;

interface DimmableLightContract
{
    /**
     * Get current brightness
     *
     * @return int|null
     */
    public function getBrightness();

    /**
     * Set brightness in percent
     *
     * @param int|string $value
     * @return Promise
     */
    public function setBrightness($value);
}