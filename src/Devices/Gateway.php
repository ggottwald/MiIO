<?php

namespace MiIO\Devices;

use MiIO\Contracts\DimmableLightContract;
use MiIO\Contracts\PowerContract;
use MiIO\Contracts\ColorableLightContract;
use MiIO\Traits\GatewayLight;

class Gateway extends BaseDevice implements ColorableLightContract, DimmableLightContract, PowerContract
{
    use GatewayLight;
}