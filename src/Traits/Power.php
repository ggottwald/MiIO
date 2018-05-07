<?php

namespace MiIO\Traits;

use MiIO\Models\Response;
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

    /**
     * @return string
     */
    public function getPower()
    {
        $result = null;

        /** @var Promise $promise */
        $promise = $this->send('get_prop', ['power']);
        $promise
            ->done(function ($response) use (&$result) {
                if ($response instanceof Response) {
                    $result = $response->getResult()[0];
                }
            }, function ($rejected) {
                // TODO: error handling
            });

        return $result;
    }
}