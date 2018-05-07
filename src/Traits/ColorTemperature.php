<?php

namespace MiIO\Traits;

use MiIO\Models\Response;
use React\Promise\Promise;

trait ColorTemperature
{
    /**
     * Get color temperature
     *
     * @return int|null
     */
    public function getColorTemperature()
    {
        $result = null;

        /** @var Promise $promise */
        $promise = $this->send('get_prop', ['cct']);
        $promise
            ->done(function ($response) use (&$result) {
                if ($response instanceof Response) {
                    $result = (int)$response->getResult()[0];
                }

                return null;
            }, function ($rejected) {
                // TODO: error handling
            });

        return $result;
    }

    /**
     * @param int|string $value
     * @return Promise
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