<?php

namespace MiIO\Devices;

use MiIO\AirPurifier\Models\Status;
use MiIO\Contracts\SensorContract;
use MiIO\Models\Response;
use MiIO\Traits\Sensor;

/**
 * Class AirPurifier
 *
 * @package MiIO\Devices
 *
 * @method start()
 * @method stop()
 * @method pause()
 * @method charge()
 * @method find()
 * @method startSpot()
 * @method Status status()
 */
class AirPurifier extends BaseDevice implements SensorContract
{
    use Sensor;

    const START_VACUUM      = 'app_start'; // Start vacuuming
    const STOP_VACUUM       = 'app_stop'; // Stop vacuuming
    const START_SPOT        = 'app_spot'; // Start spot cleaning
    const PAUSE             = 'app_pause'; // Pause cleaning
    const CHARGE            = 'app_charge'; // Start charging
    const FIND_ME           = 'find_me'; // Send findme
    const CONSUMABLES_GET   = 'get_consumable'; // Get consumables status
    const CONSUMABLES_RESET = 'reset_consumable'; // Reset consumables
    const CLEAN_SUMMARY_GET = 'get_clean_summary'; // Cleaning details
    const GET_PROP          = 'get_prop'; // Get Status information

    /**
     * @var array
     */
    private $commandList = [
        'start'         => self::START_VACUUM,
        'stop'          => self::STOP_VACUUM,
        'pause'         => self::PAUSE,
        'charge'        => self::CHARGE,
        'find'          => self::FIND_ME,
        'status'        => self::GET_PROP,
        'getConsumable' => self::CONSUMABLES_GET,
        'startSpot'     => self::START_SPOT,
    ];

    /**
     * @param string $name
     * @param array  $arguments
     * @return Status|Consumable|null
     */
    public function __call($name, $arguments)
    {
        $result = null;

        if (array_key_exists($name, $this->commandList)) {
            $params = [];

            if ($name == 'status') {
                $status = new Status();
                $params = $status->getAttributes();
            }

            $this->send($this->commandList[$name], $params)
                ->done(function ($response) use ($name, &$result) {
                    if ($response instanceof Response) {
                        switch ($name) {
                            case 'status':
                                $result = new Status($response->getResult()[0]);
                                break;
                            case 'getConsumable':
                                $result = new Consumable($response->getResult()[0]);
                        }
                    }
                }, function ($rejected) {
                    // TODO: error handling
                });
        }

        return $result;
    }
}