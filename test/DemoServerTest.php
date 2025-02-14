<?php
namespace test;

use PHPUnit\Framework\TestCase;

class DemoServerTest extends TestCase
{
    public function testInvoke()
    {
        // 创建测试对象
        $yourClass = new DemoServer();

        // 模拟函数名和数据
        $func = 'sayMsg';
        $data = '{"param1": "value1", "param2": "value2"}';

        // 调用 invoke 方法
        $result = $yourClass->invoke($func, $data);

        // 验证结果
        $this->assertIsString($result);
        $res = json_decode($result, true);
        $this->assertEquals('success', $res['msg']);
        $this->assertEquals(0, $res['code']);
    }

    public function testInvokeFunctionNotFound()
    {
        // 创建测试对象
        $yourClass = new DemoServer();

        // 模拟不存在的函数名和数据
        $func = 'nonExistingFunction';
        $data = '{"param1": "value1", "param2": "value2"}';

        // 调用 invoke 方法
        $result = $yourClass->invoke($func, $data);

        // 验证结果
        $this->assertIsString($result);
        $res = json_decode($result, true);
        $this->assertEquals('function not found:nonExistingFunction', $res['msg']);
        $this->assertEquals(1, $res['code']);
    }
}
