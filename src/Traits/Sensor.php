<?php

namespace MiIO\Traits;

use MiIO\Models\Response;

trait Sensor
{
    /**
     * @return float
     */
    public function getTemperature()
    {
        $result = null;

        $this
            ->send('get_prop', ['temp_dec'])
            ->done(function ($response) use (&$result) {
                if ($response instanceof Response) {
                    $result = $response->getResult()[0] / 10;
                }
            }, function ($rejected) {
                // TODO: error handling
            });

        return $result;
    }

    /**
     * @return int
     */
    public function getHumidity()
    {
        $result = null;

        $this
            ->send('get_prop', ['humidity'])
            ->done(function ($response) use (&$result) {
                if ($response instanceof Response) {
                    $result = (int)$response->getResult()[0];
                }
            }, function ($rejected) {
                // TODO: error handling
            });

        return $result;
    }
}