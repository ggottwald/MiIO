<?php

namespace MiIO\Traits;

use MiIO\MiIO;
use MiIO\Models\Device;

trait Light
{
    /**
     * @var MiIO
     */
    private $miIO;

    public function __construct()
    {
        $this->miIO = new MiIO();
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
     */
    public function setRgb(Device $device, $value)
    {
        if (preg_match('/^[0-9a-f]{6}$/i', $value) === false) {
            $value = dechex($value);
        }

        $bright = $this->getBrightness($device) ?? '01';

        $this->miIO->send($device, 'set_rgb', [hexdec($bright . $value)]);
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
     */
    public function setBrightness(Device $device, $value)
    {
        $bright = dechex($value);
        $rgb = $this->getRgb($device) ?? 'ffffff';

        $this->miIO->send($device, 'set_rgb', [hexdec($bright . $rgb)]);
    }

    /**
     * Get brightness an color in hex format
     *
     * @param Device $device
     * @return string|null
     */
    public function getBrightnessAndRgb(Device $device)
    {
        $response = $this->miIO->send($device, 'get_prop', ['rgb']);

        if (isset($response['result'][0])) {
            return dechex($response['result'][0]);
        }

        return null;
    }

    /**
     * Set brightness and color in hex format
     *
     * @param Device     $device
     * @param int|string $value
     */
    public function setBrightnessAndRgb(Device $device, $value)
    {
        if (preg_match('/^[0-9a-f]{7,8}$/i', $value) !== false) {
            $value = hexdec($value);
        }

        $this->miIO->send($device, 'set_rgb', [$value]);
    }

    /**
     * @param Device $device
     * @param bool   $on
     */
    public function switchPower(Device $device, $on = true)
    {
        $this->setBrightness($device, $on ? 5 : 0);
    }
}