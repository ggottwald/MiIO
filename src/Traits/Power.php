<?php

namespace MiIO\Traits;

use React\Promise\Promise;

trait Power
{
    /**
     * @param bool $on
     * @return Promise
     */
    public function setPower($on = true)
    {
        return $this->send('set_power', [$on ? 'on' : 'off']);
    }
}