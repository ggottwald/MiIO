<?php

namespace MiIO\Models;

use MiIO\Crypt;

/**
 * Class Device
 *
 * @package MiIO\Models
 */
class Device
{
	/**
	 * @var string
	 */
	private $token;

	/**
	 * @var Crypt
	 */
	private $crypt;

	/**
	 * @var string
	 */
	private $deviceName;

	/**
	 * @var string
	 */
	private $ipAddress;

	/**
	 * @var string
	 */
	private $deviceType;

	/**
	 * @var string
	 */
	private $serial;

	/**
	 * @var string
	 */
	private $model;

	/**
	 * @var int
	 */
	private $timeDelta;

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
		return !empty($this->deviceType) && !empty($this->serial);
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
}