<?php

namespace MiIO\Traits;

use MiIO\Models\Response;
use React\Promise\Promise;

trait Light
{
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
     * @param int|string $value
     * @return Promise
     */
    public function setBrightness($value): Promise
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
        $result = null;

        /** @var Promise $promise */
        $promise = $this->send('get_prop', ['rgb']);
        $promise
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
    public function switchPower($on = true): Promise
    {
        return $this->setBrightness($on ? 5 : 0);
    }
}