<?php

namespace MiIO\Traits;

use MiIO\Contracts\DimmableLightContract;
use React\Promise\Promise;
use Socket\Raw\Exception;

trait Dimmable
{
    /**
     * Get brightness
     *
     * @return int|null
     * @throws Exception
     */
    public function getBrightness()
    {
        return (int)$this->getProperties()->bright;
    }

    /**
     * Set brightness
     *
     * @param int    $value
     * @param string $effect
     * @param int    $duration
     * @return Promise
     * @throws Exception
     */
    public function setBrightness($value, $effect = DimmableLightContract::EFFECT_SMOOTH, $duration = 1000): Promise
    {
        return $this->send('set_bright', [(int)$value, $effect, $duration]);
    }
}