<?php

namespace MiIO\Devices;

use MiIO\Contracts\PowerContract;
use MiIO\Contracts\SensorContract;
use MiIO\Models\AirPurifier\Properties;
use MiIO\Models\Response;
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

    /**
     * @return Properties
     */
    public function getProperties()
    {
        $result = new Properties();
        $params = $result->getAttributes();

        $this->send('get_prop', $params)
            ->done(function ($response) use ($params, &$result) {
                if ($response instanceof Response) {
                    $attributes = array_combine($params, $response->getResult());

                    $result = new Properties($attributes);
                }
            }, function ($rejected) {
                // TODO: error handling
            });

        return $result;
    }

    /**
     * @param string $mode
     * @return Promise
     */
    public function setMode($mode)
    {
        return $this->send('set_mode', [$mode]);
    }
}