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
     * @param string $ipOrDeviceName
     * @param string $token
     * @return BaseDevice
     */
    public static function device(string $ipOrDeviceName, string $token)
    {
        $device = self::getDevice($ipOrDeviceName, $token);

        return new class($device) extends BaseDevice
        {
        };
    }

    /**
     * @param string $ipOrDeviceName
     * @param string $token
     * @return SensorContract
     */
    public static function sensorDevice(string $ipOrDeviceName, string $token)
    {
        $device = self::getDevice($ipOrDeviceName, $token);

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
     * @param string $ipOrDeviceName
     * @param string $token
     * @return AirPurifier
     */
    public static function airPurifier(string $ipOrDeviceName, string $token)
    {
        return new AirPurifier(self::getDevice($ipOrDeviceName, $token));
    }

    /**
     * @param string $ipOrDeviceName
     * @param string $token
     * @return Gateway
     */
    public static function gateway(string $ipOrDeviceName, string $token)
    {
        return new Gateway(self::getDevice($ipOrDeviceName, $token));
    }

    /**
     * @param string $ipOrDeviceName
     * @param string $token
     * @return MiRobot
     */
    public static function miRobot(string $ipOrDeviceName, string $token)
    {
        return new MiRobot(self::getDevice($ipOrDeviceName, $token));
    }

    /**
     * @param string $ipOrDeviceName
     * @param string $token
     * @return PhilipsLightBulb
     */
    public static function philipsLightBulb(string $ipOrDeviceName, string $token)
    {
        return new PhilipsLightBulb(self::getDevice($ipOrDeviceName, $token));
    }

    /**
     * @param string $ipOrDeviceName
     * @param string $token
     * @return Device
     */
    protected static function getDevice($ipOrDeviceName, $token)
    {
        $socketFactory = new \Socket\Raw\Factory();

        return new Device($socketFactory->createUdp4(), $ipOrDeviceName, $token);
    }
}
