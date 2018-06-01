<?php

namespace MiIO\Traits;

use React\Promise\Promise;
use Socket\Raw\Exception;

trait Power
{
    /**
     * @param bool $on
     * @return Promise
     * @throws Exception
     */
    public function setPower($on = true)
    {
        return $this->send('set_power', [$on ? 'on' : 'off']);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getPower()
    {
        return $this->getProperties()->power;
    }
}