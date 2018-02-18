<?php

namespace MiIO;

use MiIO\Models\Device;

class Gateway
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
     * @param Device $device
     * @return string|null
     */
    public function getRgb(Device $device)
    {
        $hexValue = $this->getRgbAndBrightness($device);

        if (strlen($hexValue) == 8) {
            return substr($hexValue, 2);
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

        $bright = $this->getBrightness($device);

        $this->miIO->send($device, 'set_rgb', [hexdec($bright . $value)]);
    }

    /**
     * @param Device $device
     * @return string|null
     */
    public function getBrightness(Device $device)
    {
        $hexValue = $this->getRgbAndBrightness($device);

        if (strlen($hexValue) == 8) {
            return substr($hexValue, 0, 2);
        }

        return null;
    }

    /**
     * @param Device     $device
     * @param int|string $value
     */
    public function setBrightness(Device $device, $value)
    {
        if (preg_match('/^[0-9a-f]{2}$/i', $value) === false) {
            $value = dechex($value);
        }

        $rgb = $this->getRgb($device);

        $this->miIO->send($device, 'set_rgb', [hexdec($value . $rgb)]);
    }

    /**
     * @param Device $device
     * @return string|null
     */
    public function getRgbAndBrightness(Device $device)
    {
        $response = $this->miIO->send($device, 'get_prop', ['rgb']);

        if (isset($response['result'][0])) {
            return dechex($response['result'][0]);
        }

        return null;
    }

    /**
     * @param Device     $device
     * @param int|string $value
     */
    public function setRgbAndBrightness(Device $device, $value)
    {
        if (preg_match('/^[0-9a-f]{8}$/i', $value) !== false) {
            $value = hexdec($value);
        }

        $this->miIO->send($device, 'set_rgb', [$value]);
    }
}