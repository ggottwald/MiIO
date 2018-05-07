<?php

namespace MiIO\Traits;

use MiIO\Models\Response;
use React\Promise\Promise;

trait Colorable
{
    /**
     * Get color in hex format
     *
     * @return string|null
     */
    public function getRgb()
    {
        $result = null;

        /** @var Promise $promise */
        $promise = $this->send('get_prop', ['color']);
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

    /**
     * @param int|string $value
     * @return Promise
     */
    public function setRgb($value): Promise
    {
        return $this->send('set_rgb', [$value]);
    }
}