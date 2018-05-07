<?php

namespace MiIO\Contracts;

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