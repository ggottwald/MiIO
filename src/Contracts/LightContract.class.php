<?php

namespace MiIO\Contracts;

use React\Promise\Promise;

interface LightContract
{
    /**
     * Get color in hex format
     *
     * @return string|null
     */
    public function getRgb();

    /**
     * @param int|string $value
     * @return Promise
     */
    public function setRgb($value);

    /**
     * Get current brightness in hex format
     *
     * @return string|null
     */
    public function getBrightness();

    /**
     * Set brightness in percent
     *
     * @param int|string $value
     * @return Promise
     */
    public function setBrightness($value);

    /**
     * Get brightness an color in hex format
     *
     * @return string|null
     */
    public function getBrightnessAndRgb();

    /**
     * Set brightness and color in hex format
     *
     * @param int|string $value
     * @return Promise
     */
    public function setBrightnessAndRgb($value);

    /**
     * @param bool $on
     * @return Promise
     */
    public function switchPower($on = true);
}