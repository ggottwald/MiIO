<?php

namespace MiIO\Models;

/**
 * Class Request
 *
 * @package MiIO\Models
 */
class Request
{
	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $method;

	/**
	 * @var array
	 */
	private $params;

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return Request
	 */
	public function setId(int $id): Request
	{
		$this->id = $id;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getMethod(): string
	{
		return $this->method;
	}

	/**
	 * @param string $method
	 * @return Request
	 */
	public function setMethod(string $method): Request
	{
		$this->method = $method;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getParams(): array
	{
		return $this->params;
	}

	/**
	 * @param array $params
	 * @return Request
	 */
	public function setParams(array $params): Request
	{
		$this->params = $params;

		return $this;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		$value = [
			'id'     => $this->id,
			'method' => $this->method,
		];

		if (is_array($this->params) && count($this->params)) {
			$value['params'] = $this->params;
		}

		return json_encode($value) . chr(0);
	}
}