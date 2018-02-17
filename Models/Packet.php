<?php

namespace App\Modules\MiIO\Models;

/**
 * Class Packet
 *
 * Container all hex strings of request and response
 *
 * @package App\Modules\MiIO\Models
 */
class Packet
{
	const MAGIC = '2131';

	/**
	 * @var string
	 */
	private $header;

	/**
	 * @var string
	 */
	private $magic;

	/**
	 * @var string
	 */
	private $length;

	/**
	 * @var string
	 */
	private $unknown;

	/**
	 * @var string
	 */
	private $timestamp;

	/**
	 * @var string
	 */
	private $checksum;

	/**
	 * @var string
	 */
	private $deviceId;

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
	private $data;

	/**
	 * @var string
	 */
	private $token;

	public function __construct($data = '')
	{
		$this->magic = static::MAGIC;
		$this->unknown = str_pad('', 8, '0');
		$this->checksum = str_pad('', 32, 'F');
		$this->setDeviceId(str_pad('', 8, 'F'));
		$this->setTimestamp(time());

		if (is_string($data)
			&& strlen($data)) {
			if (!ctype_xdigit($data)) {
				$data = bin2hex($data);
			}

			if (strlen($data) >= 64
				&& ctype_xdigit($data)
				&& strpos($data, static::MAGIC) === 0) {
				// raw data
				$this->setRAW($data);
			}
		}
	}

	/**
	 * @param string $raw
	 * @return $this
	 */
	public function setRAW($raw)
	{
		if (strlen($raw) > 32) {
			$this->header = substr($raw, 0, 32);
			$this->magic = substr($raw, 0, 4);
			$this->length = substr($raw, 4, 4);
			$this->unknown = substr($raw, 8, 8);
			$this->deviceId = substr($raw, 16, 8);
			$this->deviceType = substr($raw, 16, 4);
			$this->serial = substr($raw, 20, 4);
			$this->timestamp = substr($raw, 24, 8);
			$this->checksum = substr($raw, 32, 32);
			$this->data = substr($raw, 64);
		}

		return $this;
	}

	/**
	 * @param string $token
	 * @return $this
	 */
	public function setToken($token)
	{
		$this->token = $token;

		return $this;
	}

	/**
	 * @param int|string $timestamp
	 * @return $this
	 */
	public function setTimestamp($timestamp)
	{
		if ($timestamp && ctype_xdigit($timestamp) && strlen($timestamp) == 8) {
			$this->timestamp = $timestamp;
		} else {
			$this->timestamp = str_pad(dechex($timestamp), 8, '0', STR_PAD_LEFT);
		}

		return $this;
	}

	/**
	 * @param string $deviceId
	 * @return $this
	 */
	public function setDeviceId($deviceId)
	{
		$this->deviceId = $deviceId;
		$this->deviceType = substr($this->deviceId, 0, 4);
		$this->serial = substr($this->deviceId, 4, 4);

		return $this;
	}

	public function getDeviceId()
	{
		return $this->deviceId;
	}

	/**
	 * @param string $deviceType
	 * @return $this
	 */
	public function setDeviceType($deviceType)
	{
		$this->deviceType = $deviceType;
		$this->deviceId = $this->deviceType . $this->serial;

		return $this;
	}

	public function getDeviceType()
	{
		if (empty($this->deviceType)) {
			$this->deviceType = substr($this->deviceId, 0, 4);
		}

		return $this->deviceType;
	}

	public function setSerial($serial)
	{
		$this->serial = $serial;
		$this->deviceId = $this->deviceType . $this->serial;

		return $this;
	}

	public function getSerial()
	{
		if (empty($this->serial)) {
			$this->serial = substr($this->deviceId, 4, 4);
		}

		return $this->serial;
	}

	public function setData($data)
	{
		if (!ctype_xdigit($data)) {
			$data = bin2hex($data);
		}
		$this->data = $data;

		return $this;
	}

	public function getData()
	{
		return $this->data;
	}

	public function getTimestamp()
	{
		return $this->timestamp;
	}

	public function getHelo()
	{
		$this->magic = static::MAGIC;
		$this->unknown = str_pad('', 8, 'F');
		$this->deviceId = str_pad('', 8, 'F');
		$this->timestamp = str_pad('', 8, 'F');
		$this->checksum = str_pad('', 32, 'F');
		$this->data = '';

		return (string)$this;
	}

	/**
	 * @param Device $device
	 */
	public function setDevice(Device $device)
	{
		$this
			->setToken($device->getToken())
			->setDeviceType($device->getDeviceType())
			->setSerial($device->getSerial());
	}

	public function getLength()
	{
		$length = strlen(hex2bin($this->data)) + 32;

		return str_pad(dechex($length), 4, '0', STR_PAD_LEFT);
	}

	public function getChecksum()
	{
		return md5(
			hex2bin($this->getHeader())
			. hex2bin($this->token)
			. hex2bin($this->data)
		);
	}

	private function getHeader()
	{
		$this->header = $this->magic
			. $this->length
			. $this->unknown
			. $this->deviceId
			. $this->timestamp;

		return $this->header;
	}

	public function __toString()
	{
		$this->length = '0020';

		if (!empty($this->data)) {
			$this->length = $this->getLength();
			$this->checksum = $this->getChecksum();
		}

		return $this->getHeader()
			. $this->checksum
			. $this->data;
	}
}
