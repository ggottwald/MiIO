<?php

namespace MiIO\Models\AirPurifier;

/**
 * Class Status
 *
 * @package MiIO\Models\AirPurifier
 *
 * @property string $state_message Status message
 */
class Status implements \JsonSerializable
{
    public $power;
    public $aqi;
    public $average_aqi;
    public $humidity;
    public $temp_dec;
    public $mode;
    public $favorite_level;
    public $filter1_life;
    public $f1_hour_used;
    public $use_time;
    public $motor1_speed;
    public $motor2_speed;
    public $purify_volume;
    public $f1_hour;
    public $led;
    public $led_b;
    public $bright;
    public $buzzer;
    public $child_lock;
    public $volume;
    public $rfid_product_id;
    public $rfid_tag;
    public $act_sleep;
    public $sleep_mode;
    public $sleep_time;
    public $sleep_data_num;
    public $app_extra;
    public $act_det;
    public $button_pressed;

    protected $attributes = [
        'power',
        'aqi',
        'average_aqi',
        'humidity',
        'temp_dec',
        'mode',
        'favorite_level',
        'filter1_life',
        'f1_hour_used',
        'use_time',
        'motor1_speed',
        'motor2_speed',
        'purify_volume',
        'f1_hour',
        'led',
        'led_b',
        'bright',
        'buzzer',
        'child_lock',
        'volume',
        'rfid_product_id',
        'rfid_tag',
        'act_sleep',
        'sleep_mode',
        'sleep_time',
        'sleep_data_num',
        'app_extra',
        'act_det',
        'button_pressed',
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