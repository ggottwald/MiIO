<?php

namespace MiIO\Traits;

use React\Promise\Promise;
use Socket\Raw\Exception;

trait ColorTemperature
{
    /**
     * Get color temperature
     *
     * @return int|null
     * @throws Exception
     */
    public function getColorTemperature()
    {
        return (int)$this->getProperties()->cct;
    }

    /**
     * @param int|string $value
     * @return Promise
     * @throws Exception
     */
    public function setColorTemperature($value): Promise
    {
        if ($value > 100) {

            if ($value <= static::MIN_TEMP) {
                $value = 1;
            } elseif ($value >= static::MAX_TEMP) {
                $value = 100;
            } else {
                $value = round(($value - static::MIN_TEMP) / (static::MAX_TEMP - static::MIN_TEMP) * 100);
            }
        }
        $value = max(1, $value);

        return $this->send('set_cct', [$value]);
    }
}