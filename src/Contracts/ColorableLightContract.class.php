<?php

namespace MiIO\Contracts;

use React\Promise\Promise;

interface ColorableLightContract
{
    /**
     * Get color in hex format
     *
     * @return string|null
     */
    public function getRgb();

    /**
     * @param int|string $value
     * @return Promise
     */
    public function setRgb($value);
}