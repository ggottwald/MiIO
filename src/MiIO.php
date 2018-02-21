<?php

namespace MiIO;

use MiIO\Models\Device;
use MiIO\Models\Packet;
use MiIO\Models\Request;
use MiIO\Models\Response;
use React\Promise\Promise;
use Socket\Raw\Factory;

/**
 * Class MiIO
 *
 * @package MiIO
 */
class MiIO
{
    const CACHE_KEY = 'MiIO';

    const INFO = 'miIO.info';

    /**
     * @var Factory
     */
    protected $socketFactory;

    public function __construct(Factory $socketFactory)
    {
        $this->socketFactory = $socketFactory;
    }

    /**
     * @param string $deviceName
     * @param string $token
     * @return Device
     */
    public function createDevice(string $deviceName, string $token)
    {
        return new Device($this->socketFactory->createUdp4(), $deviceName, $token);
    }

    /**
     * @param Device $device
     * @return Promise
     */
    public function getInfo(Device $device)
    {
        $this->send($device, static::INFO);

        return $this->read($device);
    }

    /**
     * @param Device $device
     * @return Device|null
     */
    private function init(Device &$device)
    {
        $packet = new Packet();
        $helo = $packet->getHelo();

        $device->send($helo);
        $response = $device->read();

        if (!empty($response)) {
            $response = bin2hex($response);
        }

        if (!empty($response)) {

            $packet = new Packet($response);

            if ($packet->getDeviceType()
                && $packet->getSerial()) {

                $device->setDeviceType($packet->getDeviceType());
                $device->setSerial($packet->getSerial());
                $device->setTimeDelta(hexdec($packet->getTimestamp()) - time());
            }
        }

        return $device;
    }

    /**
     * @param Device $device
     * @param string $command
     * @param array  $params
     */
    public function send(Device $device, $command, $params = [])
    {
        if (!$device->isInitialized()) {
            $this->init($device);
        }

        $cacheKey = static::CACHE_KEY . $device->getIpAddress();
        $requestId = \Cache::increment($cacheKey);

        $request = new Request();
        $request
            ->setMethod($command)
            ->setParams($params)
            ->setId($requestId);

        $data = $device->encrypt($request);

        $packet = new Packet();
        $packet
            ->setData($data)
            ->setDevice($device);

        $device->send((string)$packet);
    }

    /**
     * @param Device $device
     * @return Promise
     */
    public function read(Device $device)
    {
        return new Promise(function (callable $resolve, callable $reject) use ($device) {
            $buf = $device->read();

            if (!empty($buf)) {
                $buf = bin2hex($buf);
            }

            $packet = new Packet($buf);
            $result = $device->decrypt($packet->getData());

            $response = new Response(
                json_decode(preg_replace('/[\x00-\x1F\x7F]/', '', $result), true)
            );

            if ($response->isSuccess()) {
                $resolve($response);

                return;
            }
            $reject($response->getException());
        });
    }
}