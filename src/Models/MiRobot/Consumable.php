<?php

namespace MiIO\Models\MiRobot;

/**
 * Class Consumable
 *
 * @package MiIO\Models\MiRobot
 *
 * @property int $main_brush_remain_work_time_hours
 * @property int $main_brush_work_time_percent
 * @property int $side_brush_remain_work_time_hours
 * @property int $side_brush_work_time_percent
 * @property int $filter_remain_work_time_hours
 * @property int $filter_work_time_percent
 * @property int $sensor_remain_dirty_time_hours
 * @property int $sensor_dirty_time_percent
 */
class Consumable implements \JsonSerializable
{
    /**
     * time in hours
     *
     * @var array
     */
    private static $workCycleList = [
        'main_brush_work_time' => 300,
        'side_brush_work_time' => 200,
        'filter_work_time'     => 150,
        'sensor_dirty_time'    => 30,
    ];

    /**
     * @var int
     */
    public $main_brush_work_time;

    /**
     * @var int
     */
    public $side_brush_work_time;

    /**
     * @var int
     */
    public $filter_work_time;

    /**
     * @var int
     */
    public $sensor_dirty_time;

    protected $attributes = [
        'main_brush_remain_work_time_hours',
        'main_brush_work_time',
        'main_brush_work_time_percent',
        'side_brush_work_time',
        'side_brush_remain_work_time_hours',
        'side_brush_work_time_percent',
        'filter_remain_work_time_hours',
        'filter_work_time',
        'filter_work_time_percent',
        'sensor_remain_dirty_time_hours',
        'sensor_dirty_time',
        'sensor_dirty_time_percent',
    ];

    public function __construct($attributes = [])
    {
        $this->fill($attributes);
    }

    /**
     * @param array $attributes
     */
    private function fill($attributes)
    {
        foreach ($attributes as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        switch ($key) {
            case 'main_brush_remain_work_time_hours':
                $hours = floor($this->main_brush_work_time / 60 / 60);

                return static::$workCycleList['main_brush_work_time'] - $hours;

            case 'main_brush_work_time_percent':
                return $this->getPercent('main_brush_work_time');

            case 'side_brush_remain_work_time_hours':
                $hours = floor($this->side_brush_work_time / 60 / 60);

                return static::$workCycleList['side_brush_work_time'] - $hours;

            case 'side_brush_work_time_percent':
                return $this->getPercent('side_brush_work_time');

            case 'filter_remain_work_time_hours':
                $hours = floor($this->filter_work_time / 60 / 60);

                return static::$workCycleList['filter_work_time'] - $hours;

            case 'filter_work_time_percent':
                return $this->getPercent('filter_work_time');

            case 'sensor_remain_dirty_time_hours':
                $hours = floor($this->sensor_dirty_time / 60 / 60);

                return static::$workCycleList['sensor_dirty_time'] - $hours;

            case 'sensor_dirty_time_percent':
                return $this->getPercent('sensor_dirty_time');
        }

        return null;
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

    /**
     * @param string $attribute
     * @return float
     */
    private function getPercent($attribute)
    {
        $hours = floor($this->$attribute / 60 / 60);

        return floor(100 - ($hours * 100 / static::$workCycleList[$attribute]));
    }
}