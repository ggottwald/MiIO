<?php

namespace MiIO\Traits;

use MiIO\Models\Response;
use React\Promise\Promise;

trait Dimmable
{
    /**
     * Get brightness
     *
     * @return string|null
     */
    public function getBrightness()
    {
        $result = null;

        /** @var Promise $promise */
        $promise = $this->send('get_prop', ['bright']);
        $promise
            ->done(function ($response) use (&$result) {
                if ($response instanceof Response) {
                    return $response->getResult()[0];
                }

                return null;
            }, function ($rejected) {
                // TODO: error handling
            });

        return $result;
    }

    /**
     * Set brightness
     *
     * @param int|string $value
     * @return Promise
     */
    public function setBrightness($value): Promise
    {
        return $this->send('set_bright', [$value]);
    }
}