<?php

namespace MiIO\Devices;

use MiIO\Contracts\DimmableLightContract;
use MiIO\Contracts\PowerContract;
use MiIO\Traits\Dimmable;
use MiIO\Traits\Power;

class PhilipsLightBulb extends BaseDevice implements PowerContract, DimmableLightContract
{
    const MIN_TEMP = 3000;
    const MAX_TEMP = 5700;
    
    use Power;
    use Dimmable;
}