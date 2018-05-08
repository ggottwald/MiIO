<?php

namespace MiIO\Contracts;

use React\Promise\Promise;

interface DimmableLightContract
{
    const EFFECT_SUDDEN = 'sudden';
    const EFFECT_SMOOTH = 'smooth';

    /**
     * Get current brightness
     *
     * @return int|null
     */
    public function getBrightness();

    /**
     * Set brightness in percent
     *
     * @param int    $value
     * @param string $effect
     * @param int    $duration
     * @return Promise
     */
    public function setBrightness($value, $effect = self::EFFECT_SMOOTH, $duration = 1000);
}