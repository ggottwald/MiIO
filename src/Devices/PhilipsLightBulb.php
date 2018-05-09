<?php

namespace MiIO\Devices;

use MiIO\Contracts\ColorTemperatureContract;
use MiIO\Contracts\DimmableLightContract;
use MiIO\Contracts\PowerContract;
use MiIO\Traits\ColorTemperature;
use MiIO\Traits\Dimmable;
use MiIO\Traits\Power;

class PhilipsLightBulb extends BaseDevice implements PowerContract, DimmableLightContract, ColorTemperatureContract
{
    const MIN_TEMP = 3000;
    const MAX_TEMP = 5700;

    use Power;
    use Dimmable;
    use ColorTemperature;

    protected $properties = [
        'power',
        'bright',
        'cct',
    ];
}