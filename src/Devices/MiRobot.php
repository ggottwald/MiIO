<?php

namespace MiIO\Devices;

use MiIO\Models\MiRobot\Consumable;
use MiIO\Models\MiRobot\Status;
use MiIO\Models\Response;
use React\Promise\Promise;
use Socket\Raw\Exception;

/**
 * Class MiRobot
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
 * @method Consumable getConsumable()
 */
class MiRobot extends BaseDevice
{
    const START_VACUUM      = 'app_start'; // Start vacuuming
    const STOP_VACUUM       = 'app_stop'; // Stop vacuuming
    const START_SPOT        = 'app_spot'; // Start spot cleaning
    const PAUSE             = 'app_pause'; // Pause cleaning
    const CHARGE            = 'app_charge'; // Start charging
    const FIND_ME           = 'find_me'; // Send findme
    const CONSUMABLES_GET   = 'get_consumable'; // Get consumables status
    const CONSUMABLES_RESET = 'reset_consumable'; // Reset consumables
    const CLEAN_SUMMARY_GET = 'get_clean_summary'; // Cleaning details
    const GET_STATUS        = 'get_status'; // Get Status information

    /**
     * @var array
     */
    private $commandList = [
        'start'         => self::START_VACUUM,
        'stop'          => self::STOP_VACUUM,
        'pause'         => self::PAUSE,
        'charge'        => self::CHARGE,
        'find'          => self::FIND_ME,
        'status'        => self::GET_STATUS,
        'getConsumable' => self::CONSUMABLES_GET,
        'startSpot'     => self::START_SPOT,
    ];

    /**
     * @return Promise
     * @throws Exception
     */
    public function setMode(): Promise
    {
        $mode = 60;
        $this->send('get_custom_mode')
            ->done(function ($response) use (&$mode) {
                if ($response instanceof Response) {
                    $mode = $response->getResult()[0];
                }
            }, function ($rejected) {

            });

        switch ($mode) {
            case 60:
                $mode = 77;
                break;
            case 77:
                $mode = 90;
                break;
            case 90:
                $mode = 38;
                break;
            case 38:
            default:
                $mode = 60;
                break;
        }

        return $this->send('set_custom_mode', [$mode]);
    }

    /**
     * @param string $name
     * @param array  $arguments
     * @return Status|Consumable|null
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        $result = null;

        if (array_key_exists($name, $this->commandList)) {

            $this->send($this->commandList[$name])
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