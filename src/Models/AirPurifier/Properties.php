<?php

namespace MiIO\Models\AirPurifier;

/**
 * Class Status
 *
 * @package MiIO\Models\AirPurifier
 */
class Properties implements \JsonSerializable
{
	public $power;
	public $mode;
	public $temp_dec;
	public $humidity;
	public $aqi;
	public $favorite_level;
	public $filter1_life;
	public $f1_hour_used;
	public $use_time;
	public $led;
	public $led_b;
	public $buzzer;

	protected $attributes = [
		'power',
		'mode',
		'temp_dec',
		'humidity',
		'aqi',
		'favorite_level',
		'filter1_life',
		'f1_hour_used',
		'use_time',
		'led',
		'led_b',
		'buzzer',
	];

	public function __construct($attributes = [])
	{
		$this->fill($attributes);
	}

	/**
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 * @param array $attributes
	 */
	public function fill($attributes)
	{
		foreach ($attributes as $key => $value) {
			if (property_exists($this, $key)) {
				$this->$key = $value;
			}
		}
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function __isset($name)
	{
		return $this->$name !== null;
	}

	public function jsonSerialize()
	{
		$attributeList = [];

		foreach ($this->attributes as $attribute) {
			$attributeList[$attribute] = $this->$attribute;
		}

		return $attributeList;
	}
}