<?php

namespace MiIO\Devices;

use MiIO\Contracts\LightContract;
use MiIO\Traits\Light;

class Gateway extends BaseDevice implements LightContract
{
    use Light;
}