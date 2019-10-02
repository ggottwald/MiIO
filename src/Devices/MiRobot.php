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
    const START_VACUUM         = 'app_start'; // Start vacuuming
    const STOP_VACUUM          = 'app_stop'; // Stop vacuuming
    const START_SPOT           = 'app_spot'; // Start spot cleaning
    const PAUSE                = 'app_pause'; // Pause cleaning
    const CHARGE               = 'app_charge'; // Start charging
    const FIND_ME              = 'find_me'; // Send findme
    const CONSUMABLES_GET      = 'get_consumable'; // Get consumables status
    const CONSUMABLES_RESET    = 'reset_consumable'; // Reset consumables
    const CLEAN_SUMMARY_GET    = 'get_clean_summary'; // Cleaning details
    const CLEAN_RECORD_GET     = 'get_clean_record'; // Cleaning details
    const CLEAN_RECORD_MAP_GET = 'get_clean_record_map'; // Get the map reference of a historical cleaning
    const GET_STATUS           = 'get_status'; // Get Status information
    const GET_SERIAL_NUMBER    = 'get_serial_number'; // Get Serial #
    const DND_GET              = 'get_dnd_timer'; // Do Not Disturb Settings
    const DND_SET              = 'set_dnd_timer'; // Set the do not disturb timings
    const DND_CLOSE            = 'close_dnd_timer'; // Disable the do not disturb function
    const TIMER_SET            = 'set_timer'; // Add a timer
    const TIMER_UPDATE         = 'upd_timer'; // Activate/deactivate a timer
    const TIMER_GET            = 'get_timer'; // Get Timers
    const TIMER_DEL            = 'del_timer'; // Remove a timer
    const TIMERZONE_GET        = 'get_timezone'; // Get timezone
    const TIMERZONE_SET        = 'set_timezone'; // Set timezone
    const SOUND_INSTALL        = 'dnld_install_sound'; // Voice pack installation
    const SOUND_GET_CURRENT    = 'get_current_sound'; // Current voice
    const SOUND_GET_VOLUME     = 'get_sound_volume';
    const LOG_UPLOAD_GET       = 'get_log_upload_status';
    const LOG_UPLOAD_ENABLE    = 'enable_log_upload';
    const SET_MODE             = 'set_custom_mode'; // Set the vacuum level
    const GET_MODE             = 'get_custom_mode'; // Get the vacuum level
    const REMOTE_START         = 'app_rc_start'; // Start remote control
    const REMOTE_END           = 'app_rc_end'; // End remote control
    const REMOTE_MOVE          = 'app_rc_move'; // Remote control move command
    const GET_GATEWAY          = 'get_gateway'; // Get current gateway

    /**
     * @var array
     */
    private $commandList = [
        'start'                    => self::START_VACUUM,
        'stop'                     => self::STOP_VACUUM,
        'pause'                    => self::PAUSE,
        'charge'                   => self::CHARGE,
        'find'                     => self::FIND_ME,
        'status'                   => self::GET_STATUS,
        'getConsumable'            => self::CONSUMABLES_GET,
        'startSpot'                => self::START_SPOT,
        self::CONSUMABLES_RESET    => self::CONSUMABLES_RESET,
        self::CLEAN_SUMMARY_GET    => self::CLEAN_SUMMARY_GET,
        self::CLEAN_RECORD_GET     => self::CLEAN_RECORD_GET,
        self::CLEAN_RECORD_MAP_GET => self::CLEAN_RECORD_MAP_GET,
        self::GET_SERIAL_NUMBER    => self::GET_SERIAL_NUMBER,
        self::DND_GET              => self::DND_GET,
        self::DND_SET              => self::DND_SET,
        self::DND_CLOSE            => self::DND_CLOSE,
        self::TIMER_SET            => self::TIMER_SET,
        self::TIMER_UPDATE         => self::TIMER_UPDATE,
        self::TIMER_GET            => self::TIMER_GET,
        self::TIMER_DEL            => self::TIMER_DEL,
        self::TIMERZONE_GET        => self::TIMERZONE_GET,
        self::TIMERZONE_SET        => self::TIMERZONE_SET,
        self::SOUND_INSTALL        => self::SOUND_INSTALL,
        self::SOUND_GET_CURRENT    => self::SOUND_GET_CURRENT,
        self::SOUND_GET_VOLUME     => self::SOUND_GET_VOLUME,
        self::LOG_UPLOAD_GET       => self::LOG_UPLOAD_GET,
        self::LOG_UPLOAD_ENABLE    => self::LOG_UPLOAD_ENABLE,
        self::SET_MODE             => self::SET_MODE,
        self::GET_MODE             => self::GET_MODE,
        self::REMOTE_START         => self::REMOTE_START,
        self::REMOTE_END           => self::REMOTE_END,
        self::REMOTE_MOVE          => self::REMOTE_MOVE,
        self::GET_GATEWAY          => self::GET_GATEWAY,

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
