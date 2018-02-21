<?php

namespace MiIO\Models;

use MiIO\Exceptions\DeviceCommandException;

class Response
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var array
     */
    private $result = [];

    /**
     * @var DeviceCommandException|null
     */
    private $exception = null;

    /**
     * Response constructor.
     *
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->id = $response['id'];
        if (isset($response['error'])) {
            $this->exception = new DeviceCommandException(
                $response['error']['message'],
                $response['error']['code'],
                $response['id']
            );
        } else {
            $this->result = $response['result'];
        }
    }

    /**
     * @return int
     */
    public function getDeviceId(): int
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getResult(): array
    {
        return $this->result;
    }

    /**
     * @return null|DeviceCommandException
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return is_null($this->exception);
    }
}
