<?php
require __DIR__ . '/../vendor/autoload.php';

class TransmitHandler extends \bingher\transmit\Server
{
    public function sayMsg($params)
    {
        return 'you say:' . var_export($params, true);
    }
}

$host = '0.0.0.0';
$port = 9000;

TransmitHandler::run($port, $host);
