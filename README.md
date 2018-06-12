# MiIO
With this package you can control Xiaomi Mi Home devices that implement the miIO protocol, such as the
Mi Robot Vacuum and other Smart Home devices.
These devices are commonly part of what Xiaomi calls the Mi Ecosystem which is branded as MiJia.

# Example
Get device start cleaning and get status of mi robot vacuum
```php
// mir robot vacuum
$robotDeviceName = 'mirobot_vacuum';
$robotToken = '00112233445566778899aabbccddeeff';

$miRobot = Factory::miRobot($robotDeviceName, $robotToken);

// start cleaning
$miRobot->start();

// get status
var_export($miRobot->status());
```

Set power of mi air purifier
```php
// air purifier
$purifierDeviceName = 'air_purifier';
$purifierToken = '00112233445566778899aabbccddeeff';

$purifier = Factory::airPurifier($purifierDeviceName, $purifierToken);

$purifier->setPower(true);
```

More information about the protocol and commands can be found at
https://github.com/marcelrv/XiaomiRobotVacuumProtocol

## License

This project is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE - see the [LICENSE.md](/LICENSE.md) file for details.