<?php

namespace MiIO\Models;

use MiIO\Crypt;
use Socket\Raw\Socket;

/**
 * Class Device
 *
 * @package MiIO\Models
 */
class Device
{
    const PORT = 54321;

    const PACKET_LENGTH = 1024;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var Crypt
     */
    protected $crypt;

    /**
     * @var string
     */
    protected $deviceName;

    /**
     * @var string
     */
    protected $ipAddress;

    /**
     * @var int
     */
    protected $port = self::PORT;

    /**
     * @var string
     */
    protected $deviceType;

    /**
     * @var string
     */
    protected $serial;

    /**
     * @var string
     */
    protected $model;

    /**
     * @var int
     */
    protected $timeDelta;

    /**
     * @var Socket
     */
    protected $socket;

    public function __construct(Socket $socket, string $deviceName, string $token)
    {
        $this->socket = $socket;
        $this->deviceName = $deviceName;
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Device
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return Device
     */
    public function setToken(string $token): Device
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @param string $data
     * @return string
     */
    public function decrypt($data): string
    {
        return $this->getCrypt()->decrypt($data);
    }

    /**
     * @param string $data
     * @return string
     */
    public function encrypt($data): string
    {
        return $this->getCrypt()->encrypt($data);
    }

    /**
     * @return string
     */
    public function getDeviceName(): string
    {
        return $this->deviceName;
    }

    /**
     * @param string $deviceName
     * @return Device
     */
    public function setDeviceName(string $deviceName): Device
    {
        $this->deviceName = $deviceName;

        return $this;
    }

    /**
     * @return string
     */
    public function getIpAddress(): string
    {
        if (empty($this->ipAddress) && !empty($this->deviceName)) {
            $this->ipAddress = gethostbyname($this->deviceName);
        }

        return $this->ipAddress;
    }

    /**
     * @param string $ipAddress
     * @return Device
     */
    public function setIpAddress(string $ipAddress): Device
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @param int $port
     */
    public function setPort(int $port)
    {
        $this->port = $port;
    }

    /**
     * @return string
     */
    public function getRemote()
    {
        return sprintf('%s:%d', $this->getIpAddress(), $this->getPort());
    }

    /**
     * @return string
     */
    public function getDeviceType(): string
    {
        return $this->deviceType;
    }

    /**
     * @param string $deviceType
     * @return Device
     */
    public function setDeviceType(string $deviceType): Device
    {
        $this->deviceType = $deviceType;

        return $this;
    }

    /**
     * @return string
     */
    public function getSerial(): string
    {
        return $this->serial;
    }

    /**
     * @param string $serial
     * @return Device
     */
    public function setSerial(string $serial): Device
    {
        $this->serial = $serial;

        return $this;
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @param string $model
     * @return Device
     */
    public function setModel(string $model): Device
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return int
     */
    public function getTimeDelta(): int
    {
        return $this->timeDelta;
    }

    /**
     * @param int $timeDelta
     * @return Device
     */
    public function setTimeDelta(int $timeDelta): Device
    {
        $this->timeDelta = $timeDelta;

        return $this;
    }

    /**
     * @return bool
     */
    public function isInitialized()
    {
        return !empty($this->deviceType) && !empty($this->serial) && $this->socket instanceof Socket;
    }

    /**
     * @return Crypt
     */
    private function getCrypt()
    {
        if (!$this->crypt instanceof Crypt) {
            $this->crypt = new Crypt($this->token);
        }

        return $this->crypt;
    }

    /**
     * @return Socket
     */
    public function getSocket()
    {
        return $this->socket;
    }

    /**
     * @param Socket $socket
     * @return Device
     */
    public function setSocket(Socket $socket)
    {
        $this->socket = $socket;

        return $this;
    }

    /**
     * @param $data
     */
    public function send($data)
    {
        if (ctype_xdigit($data)) {
            $data = hex2bin($data);
        }

        $this->socket
            ->setOption(SOL_SOCKET, SO_BROADCAST, 1)
            ->setOption(SOL_SOCKET, SO_RCVTIMEO, ['sec' => 5, 'usec' => 0])
            ->sendTo($data, 0, $this->getRemote());
    }

    /**
     * @return string
     */
    public function read()
    {
        $remote = $this->getRemote();

        return $this->socket->recvFrom(self::PACKET_LENGTH, 0, $remote);
    }
}