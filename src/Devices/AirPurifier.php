<?php

namespace MiIO\Devices;

use MiIO\Contracts\PowerContract;
use MiIO\Contracts\SensorContract;
use MiIO\Traits\Power;
use MiIO\Traits\Sensor;
use React\Promise\Promise;

/**
 * Class AirPurifier
 *
 * @package MiIO\Devices
 */
class AirPurifier extends BaseDevice implements SensorContract, PowerContract
{
    use Sensor;
    use Power;

    const MODE_IDLE     = 'idle';
    const MODE_AUTO     = 'auto';
    const MODE_SILENT   = 'silent';
    const MODE_FAVORITE = 'favorite';

    protected $properties = [
        'power',
        'mode',
        'temp_dec',
        'humidity',
        'aqi',
        'favorite_level',
        'filter1_life',
        'f1_hour_used',
        'use_time',
        'led',
        'led_b',
        'buzzer',
        'purify_volume',
        'learn_mode',
    ];

    /**
     * @param string $mode
     * @return Promise
     */
    public function setMode($mode)
    {
        return $this->send('set_mode', [$mode]);
    }
}