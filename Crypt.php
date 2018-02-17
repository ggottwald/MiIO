<?php

namespace App\Modules\MiIO;

class Crypt
{
	const TOKEN_LENGTH = 32;

	/**
	 * @var string
	 */
	private $key;

	/**
	 * @var string
	 */
	private $iv;

	/**
	 * @var string
	 */
	private $token;

	/**
	 * Crypt constructor.
	 *
	 * @param string $token
	 */
	public function __construct($token)
	{
		if (ctype_xdigit($token)) {
			$token = hex2bin($token);
		}
		$this->token = $token;
		if ($this->checkToken()) {
			$this->key = hex2bin(md5($token));
			$this->iv = hex2bin(md5($this->key . $token));
		}
	}

	/**
	 * @param string $data
	 * @return string
	 */
	public function decrypt($data)
	{
		if ($this->checkToken()) {
			if (ctype_xdigit($data)) {
				$data = hex2bin($data);
			}

			return openssl_decrypt($data, 'aes-128-cbc', $this->key, OPENSSL_RAW_DATA, $this->iv);
		}
	}

	/**
	 * @param string $data
	 * @return string
	 */
	public function encrypt($data)
	{
		if ($this->checkToken()) {
			if (ctype_xdigit($data)) {
				$data = hex2bin($data);
			}

			return openssl_encrypt($data, 'aes-128-cbc', $this->key, OPENSSL_RAW_DATA, $this->iv);
		}
	}

	/**
	 * @return string
	 */
	public function getToken()
	{
		return $this->token;
	}

	/**
	 * @return bool
	 */
	private function checkToken()
	{
		return strlen(bin2hex($this->token)) == static::TOKEN_LENGTH;
	}
}