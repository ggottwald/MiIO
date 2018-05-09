<?php

namespace MiIO\Devices;

use MiIO\Contracts\DimmableLightContract;
use MiIO\Contracts\PowerContract;
use MiIO\Contracts\ColorableLightContract;
use React\Promise\Promise;

class Gateway extends BaseDevice implements ColorableLightContract, DimmableLightContract, PowerContract
{
    protected $properties = [
        'rgb',
    ];

    /**
     * Get color in hex format
     *
     * @return string|null
     */
    public function getRgb()
    {
        $hexValue = $this->getBrightnessAndRgb();

        if (strlen($hexValue) >= 7) {
            return strrev(substr(strrev($hexValue), 0, 6));
        }

        return null;
    }

    /**
     * @param int|string $value
     * @return Promise
     */
    public function setRgb($value): Promise
    {
        if (preg_match('/^[0-9a-f]{6}$/i', $value) === false) {
            $value = dechex($value);
        }

        $bright = $this->getBrightness() ?? '01';

        return $this->send('set_rgb', [hexdec($bright . $value)]);
    }

    /**
     * Get current brightness in hex format
     *
     * @return string|null
     */
    public function getBrightness()
    {
        $hexValue = $this->getBrightnessAndRgb();

        if (strlen($hexValue) >= 7) {
            return strrev(substr(strrev($hexValue), 6));
        }

        return null;
    }

    /**
     * Set brightness in percent
     *
     * @param int    $value
     * @param string $effect
     * @param int    $duration
     * @return Promise
     */
    public function setBrightness($value, $effect = DimmableLightContract::EFFECT_SMOOTH, $duration = 1000): Promise
    {
        $bright = dechex($value);
        $rgb    = $this->getRgb() ?? 'ffffff';

        return $this->send('set_rgb', [hexdec($bright . $rgb)]);
    }

    /**
     * Get brightness an color in hex format
     *
     * @return string|null
     */
    public function getBrightnessAndRgb()
    {
        return dechex($this->getProperties()->rgb);
    }

    /**
     * Set brightness and color in hex format
     *
     * @param int|string $value
     * @return Promise
     */
    public function setBrightnessAndRgb($value): Promise
    {
        if (preg_match('/^[0-9a-f]{7,8}$/i', $value) !== false) {
            $value = hexdec($value);
        }

        return $this->send('set_rgb', [$value]);
    }

    /**
     * @param bool $on
     * @return Promise
     */
    public function setPower($on = true): Promise
    {
        return $this->setBrightness($on ? 5 : 0);
    }

    /**
     * Get current power state
     *
     * @return string|null
     */
    public function getPower()
    {
        return $this->getBrightness() > 0 ? 'on' : 'off';
    }
}