<?php

namespace MiIO;

use MiIO\Models\Device;
use MiIO\Models\Packet;
use MiIO\Models\Request;
use MiIO\Models\Response;
use React\Promise\Promise;
use Socket\Raw\Exception;

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
     * @param Device $device
     * @return array
     */
    public function getInfo(Device $device)
    {
        return $this->send($device, static::INFO)
            ->done(function ($response) {
                if ($response instanceof Response) {
                    return $response->getResult()[0];
                }

                return null;
            }, function ($rejected) {
                // TODO: error handling
            });
    }

    /**
     * @param Device $device
     * @return Device|null
     * @throws Exception
     */
    private function init(Device &$device)
    {
        $packet = new Packet();
        $helo   = $packet->getHelo();

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
     * @return Promise
     * @throws Exception
     */
    public function send(Device $device, $command, $params = [])
    {
        if (!$device->isInitialized()) {
            $this->init($device);
        }

        $cacheKey  = static::CACHE_KEY . $device->getIpAddress();
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

        return $this->read($device);
    }

    /**
     * @param Device $device
     * @return Promise
     * @throws Exception
     */
    protected function read(Device $device)
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