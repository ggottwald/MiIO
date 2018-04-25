<?php

namespace MiIO\Contracts;

use React\Promise\Promise;

interface SensorContract
{
	/**
	 * @return float
	 */
	public function getTemperature();

	/**
	 * @return int
	 */
	public function getHumidity();
}