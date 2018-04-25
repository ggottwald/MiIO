<?php

namespace MiIO\Models\MiRobot;

/**
 * Class Status
 *
 * @package MiIO\Models\MiRobot
 *
 * @property string $state_message Status message
 */
class Status implements \JsonSerializable
{
    private static $statusCodes = [
        0   => 'Unknown',
        1   => 'Initiating',
        2   => 'Sleeping',
        3   => 'Waiting',
        4   => '?',
        5   => 'Cleaning',
        6   => 'Back to home',
        7   => '?',
        8   => 'Charging',
        9   => 'Charging Error',
        10  => 'Pause',
        11  => 'Spot Cleaning',
        12  => 'In Error',
        13  => 'Shutting down',
        14  => 'Updating',
        15  => 'Docking',
        100 => 'Full',
    ];

    /**
     * Battery level
     *
     * @var int
     */
    public $battery;

    /**
     * total area (in cm2)
     *
     * @var int
     */
    public $clean_area;

    /**
     * Total cleaning time in sec
     *
     * @var int
     */
    public $clean_time;

    /**
     * Is Do Not Disturb enabled (0=disabled)
     *
     * @var int
     */
    public $dnd_enabled;

    /**
     * Error code (0=no error. see list below)
     *
     * @var int
     */
    public $error_code;

    /**
     * Fan power
     *
     * @var int
     */
    public $fan_power;

    /**
     * Is device cleaning
     *
     * @var int
     */
    public $in_cleaning;

    /**
     * Is map present
     *
     * @var int
     */
    public $map_present;

    /**
     * Message sequence increments with each request
     *
     * @var int
     */
    public $msg_seq;

    /**
     * Message version (seems always 4)
     *
     * @var int
     */
    public $msg_ver;

    /**
     * Status code (see list below)
     *
     * @var int
     */
    public $state;

    protected $attributes = [
        'battery',
        'clean_area',
        'clean_time',
        'dnd_enabled',
        'error_code',
        'fan_power',
        'in_cleaning',
        'map_present',
        'msg_seq',
        'msg_ver',
        'state',
        'state_message',
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
        if ($key == 'state_message') {
            return static::$statusCodes[$this->state] ?? $this->state;
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
}