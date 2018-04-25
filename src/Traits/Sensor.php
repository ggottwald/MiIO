<?php

namespace MiIO\Traits;

use MiIO\Models\Response;
use React\Promise\Promise;

trait Sensor
{
	/**
	 * @return float
	 */
	public function getTemperature()
	{
		$result = null;

		$this
			->send('get_prop', ['temp_dec'])
			->done(function ($response) use (&$result) {
				if ($response instanceof Response) {
					$result = $response->getResult()[0] / 10;
				}
			}, function ($rejected) {
				// TODO: error handling
			});

		return $result;
	}

	/**
	 * @return int
	 */
	public function getHumidity()
	{

	}
}