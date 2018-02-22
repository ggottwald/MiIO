<?php

namespace MiIO\Traits;

use MiIO\MiIO;
use MiIO\Models\Device;
use MiIO\Models\Response;
use React\Promise\Promise;
use Socket\Raw\Factory;

trait Light
{
    /**
     * @var MiIO
     */
    private $miIO;

    public function __construct()
    {
        $this->miIO = new MiIO(new Factory());
    }

    /**
     * Get color in hex format
     *
     * @param Device $device
     * @return string|null
     */
    public function getRgb(Device $device)
    {
        $hexValue = $this->getBrightnessAndRgb($device);

        if (strlen($hexValue) >= 7) {
            return strrev(substr(strrev($hexValue), 0, 6));
        }

        return null;
    }

    /**
     * @param Device     $device
     * @param int|string $value
     * @return Promise
     */
    public function setRgb(Device $device, $value): Promise
    {
        if (preg_match('/^[0-9a-f]{6}$/i', $value) === false) {
            $value = dechex($value);
        }

        $bright = $this->getBrightness($device) ?? '01';

        return $this->miIO->send($device, 'set_rgb', [hexdec($bright . $value)]);
    }

    /**
     * Get current brightness in hex format
     *
     * @param Device $device
     * @return string|null
     */
    public function getBrightness(Device $device)
    {
        $hexValue = $this->getBrightnessAndRgb($device);

        if (strlen($hexValue) >= 7) {
            return strrev(substr(strrev($hexValue), 6));
        }

        return null;
    }

    /**
     * Set brightness in percent
     *
     * @param Device     $device
     * @param int|string $value
     * @return Promise
     */
    public function setBrightness(Device $device, $value): Promise
    {
        $bright = dechex($value);
        $rgb = $this->getRgb($device) ?? 'ffffff';

        return $this->miIO->send($device, 'set_rgb', [hexdec($bright . $rgb)]);
    }

    /**
     * Get brightness an color in hex format
     *
     * @param Device $device
     * @return string|null
     */
    public function getBrightnessAndRgb(Device $device)
    {
        $result = null;

        $this->miIO->send($device, 'get_prop', ['rgb'])
            ->done(function ($response) use (&$result) {
                if ($response instanceof Response) {
                    $result = dechex($response->getResult()[0]);
                }
            }, function ($rejected) {
                // TODO: error handling
            });

        return $result;
    }

    /**
     * Set brightness and color in hex format
     *
     * @param Device     $device
     * @param int|string $value
     * @return Promise
     */
    public function setBrightnessAndRgb(Device $device, $value): Promise
    {
        if (preg_match('/^[0-9a-f]{7,8}$/i', $value) !== false) {
            $value = hexdec($value);
        }

        return $this->miIO->send($device, 'set_rgb', [$value]);
    }

    /**
     * @param Device $device
     * @param bool   $on
     * @return Promise
     */
    public function switchPower(Device $device, $on = true): Promise
    {
        return $this->setBrightness($device, $on ? 5 : 0);
    }
}