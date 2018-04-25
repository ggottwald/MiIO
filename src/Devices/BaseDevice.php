<?php

namespace MiIO\Devices;

use MiIO\MiIO;
use MiIO\Models\Device;
use React\Promise\Promise;

/**
 * Class BaseDevice
 * @package MiIO\Devices
 */
abstract class BaseDevice
{
    /**
     * @var Device
     */
    protected $device;

    /**
     * @var MiIO
     */
    protected $miIO;

    /**
     * MiRobot constructor.
     * @param Device $device
     */
    public function __construct(Device $device)
    {
        $this->device = $device;
        $this->miIO   = new MiIO();
    }

    /**
     * @param string $command
     * @param array  $params
     * @return Promise
     */
    public function send($command, $params = [])
    {
        return $this->miIO->send($this->device, $command, $params);
    }

    /**
     * @return string
     */
    public function getDeviceName()
    {
        return $this->device->getDeviceName();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->device->getName();
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->device->setName($name);

        return $this;
    }
}