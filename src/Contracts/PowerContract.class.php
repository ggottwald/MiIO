<?php

namespace MiIO\Contracts;

use React\Promise\Promise;

interface PowerContract
{
    /**
     * @param bool $on
     * @return Promise
     */
    public function setPower($on = true);
}