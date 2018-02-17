<?php

namespace MiIO;

use MiIO\Models\Device;
use MiIO\Models\Packet;
use MiIO\Models\Request;

/**
 * Class MiIO
 *
 * @package MiIO
 */
class MiIO
{
    const CACHE_KEY = 'MiIO';

    const PORT = 54321;

    const TIMEOUT = 5;

    const INFO = 'miIO.info';

    /**
     * @param Device $device
     * @return array
     */
    public function getInfo(Device $device)
    {
        return $this->send($device, static::INFO);
    }

    /**
     * @param Device $device
     * @return Device|null
     */
    private function init(Device &$device)
    {
        if (strlen($device->getIpAddress())) {
            $packet = new Packet();
            $helo = $packet->getHelo();

            $start = time();
            $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

            while (time() < ($start + 10)) {
                $response = $this->getSocketResponse($device->getIpAddress(), $socket, $helo);

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
                        socket_close($socket);

                        return $device;
                    }
                }
            }
            if ($socket) {
                socket_close($socket);
            }
        }

        return null;
    }

    /**
     * @param Device $device
     * @param string $command
     * @param array  $params
     * @return array
     */
    public function send(Device $device, $command, $params = [])
    {
        if (!$device->isInitialized()) {
            $this->init($device);
        }

        if (!$device->isInitialized()) {
            return [];
        }

        $request = new Request();
        $request
            ->setMethod($command)
            ->setParams($params);

        return $this->getResponse($device, $request);
    }

    /**
     * @param Device  $device
     * @param Request $request
     * @return array
     */
    public function getResponse(Device $device, Request $request)
    {
        $cacheKey = static::CACHE_KEY . $device->getIpAddress();
        $requestId = (int)\Cache::get($cacheKey);
        \Cache::forever($cacheKey, $requestId + 1);

        $request->setId($requestId);

        $data = $device->encrypt($request);

        $packet = new Packet();
        $packet
            ->setData($data)
            ->setDevice($device);

        $start = time();
        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

        while (time() < ($start + 10)) {
            $packet->setTimestamp($device->getTimeDelta() + time());
            $response = $this->getSocketResponse($device->getIpAddress(), $socket, (string)$packet);

            if (!empty($response)) {
                $response = bin2hex($response);
            }

            $result = $this->decrypt($device, $response);

            if (strlen($result)) {
                $response = json_decode(preg_replace('/[\x00-\x1F\x7F]/', '', $result), true);

                if (!empty($response['id']) && $response['id'] === $requestId) {
                    socket_close($socket);

                    return $response;
                }
            }
        }

        return [];
    }

    /**
     * @param string    $ip
     * @param \Resource $socket
     * @param string    $data
     * @return string|null
     */
    private function getSocketResponse($ip, $socket, $data)
    {
        if (ctype_xdigit($data)) {
            $data = hex2bin($data);
        }

        $buf = null;

        try {
            socket_set_option($socket, SOL_SOCKET, SO_BROADCAST, 1);
            socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, ['sec' => 5, 'usec' => 0]);

            socket_sendto($socket, $data, strlen($data), 0, $ip, static::PORT);

            socket_recvfrom($socket, $buf, 1024, 0, $name, $port);
        } catch (\Throwable $e) {
        }

        return $buf;
    }

    /**
     * @param Device $device
     * @param string $response
     * @return string
     */
    private function decrypt(Device $device, $response)
    {
        if (!empty($response)) {
            $packet = new Packet($response);

            return $device->decrypt($packet->getData());
        }

        return '';
    }
}