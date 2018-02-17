# MiIO
With this package you can control Xiaomi Mi Home devices that implement the miIO protocol, such as the
Mi Robot Vacuum and other Smart Home devices.
These devices are commonly part of what Xiaomi calls the Mi Ecosystem which is branded as MiJia.

# Example
Get device info, status and start cleaning of mi robot vacuum

    $device = new Device();
    
    $device
        ->setDeviceName('mirobot_vacuum')
        ->setToken('00112233445566778899aabbccddeeff');
        
    $miio = new MiIO();
    
    $deviceInfoResult = $miio->getInfo($device);
    
    $statusResult = $miio->send($device, 'get_status');
    
    $miio->send($device, 'app_start'); // start cleaning
    
 
More information about the protocol and commands can be found at
https://github.com/marcelrv/XiaomiRobotVacuumProtocol

## License

This project is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE - see the [LICENSE.md](/LICENSE.md) file for details.