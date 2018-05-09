<?php

namespace MiIO\Traits;

use React\Promise\Promise;

trait Colorable
{
    /**
     * Get color in hex format
     *
     * @return string|null
     */
    public function getRgb()
    {
        return $this->getProperties()->rgb;
    }

    /**
     * @param int|string $value
     * @return Promise
     */
    public function setRgb($value): Promise
    {
        return $this->send('set_rgb', [$value]);
    }
}