<?php
namespace test;

use PHPUnit\Framework\TestCase;
use bingher\transmit\Client;

class ClientTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        // 定义要执行的命令
        $command = __DIR__ . '/server.bat';
        shell_exec($command);
    }
    public function test()
    {
        $c      = new Client();
        $result = $c->sayMsg(['params' => 'hello']);
        var_dump($result);
        $this->assertIsString($result);
        $res = json_decode($result, true);
        $this->assertEquals('success', $res['msg']);
        $this->assertEquals(0, $res['code']);
    }
}
