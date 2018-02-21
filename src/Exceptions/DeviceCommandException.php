<?php

namespace MiIO\Exceptions;

class DeviceCommandException extends Exception
{
    /**
     * @var int
     */
    private $deviceId;

    /**
     * DeviceCommandException constructor.
     *
     * @param string $message
     * @param int    $code
     * @param int    $deviceId
     */
    public function __construct(string $message, int $code, int $deviceId)
    {
        parent::__construct($message, $code);
        $this->deviceId = $deviceId;
    }

    /**
     * @return int
     */
    public function getDeviceId(): int
    {
        return $this->deviceId;
    }
}
