<?php

namespace MiIO;

use MiIO\Contracts\SensorContract;
use MiIO\Devices\AirPurifier;
use MiIO\Devices\BaseDevice;
use MiIO\Devices\Gateway;
use MiIO\Devices\MiRobot;
use MiIO\Devices\PhilipsLightBulb;
use MiIO\Models\Device;
use MiIO\Traits\Sensor;

/**
 * Class MiIO
 *
 * @package MiIO
 */
class Factory
{
    /**
     * @param string $deviceName
     * @param string $token
     * @return BaseDevice
     */
    public static function device(string $deviceName, string $token)
    {
        $device = self::getDevice($deviceName, $token);

        return new class($device) extends BaseDevice
        {
        };
    }

    /**
     * @param string $deviceName
     * @param string $token
     * @return SensorContract
     */
    public static function sensorDevice(string $deviceName, string $token)
    {
        $device = self::getDevice($deviceName, $token);

        return new class($device) extends BaseDevice implements SensorContract
        {
            use Sensor;

            protected $properties = [
                'temp_dec',
                'humidity',
            ];
        };
    }

    /**
     * @param string $deviceName
     * @param string $token
     * @return AirPurifier
     */
    public static function airPurifier(string $deviceName, string $token)
    {
        return new AirPurifier(self::getDevice($deviceName, $token));
    }

    /**
     * @param string $deviceName
     * @param string $token
     * @return Gateway
     */
    public static function gateway(string $deviceName, string $token)
    {
        return new Gateway(self::getDevice($deviceName, $token));
    }

    /**
     * @param string $deviceName
     * @param string $token
     * @return MiRobot
     */
    public static function miRobot(string $deviceName, string $token)
    {
        return new MiRobot(self::getDevice($deviceName, $token));
    }

    /**
     * @param string $deviceName
     * @param string $token
     * @return PhilipsLightBulb
     */
    public static function philipsLightBulb(string $deviceName, string $token)
    {
        return new PhilipsLightBulb(self::getDevice($deviceName, $token));
    }

    /**
     * @param string $deviceName
     * @param string $token
     * @return Device
     */
    protected static function getDevice($deviceName, $token)
    {
        $socketFactory = new \Socket\Raw\Factory();

        return new Device($socketFactory->createUdp4(), $deviceName, $token);
    }
}