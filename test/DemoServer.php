<?php
namespace test;

use bingher\transmit\Server;

class DemoServer extends Server
{
    public function sayMsg($params)
    {
        return 'you say:' . var_export($params, true);
    }
}
