<?php
namespace bingher\transmit;

use Thrift\Exception\TException;
use Thrift\Factory\TBinaryProtocolFactory;
use Thrift\Factory\TTransportFactory;
use Thrift\Server\TServerSocket;
use Thrift\Server\TSimpleServer;

class Server implements TransmitIf
{
    public function invoke($func, $data)
    {
        if (!method_exists($this, $func)) {
            return 'function not found:' . $func;
        }
        $params = json_decode($data, true);
        return call_user_func([$this, $func], $params);
    }

    public static function run($port = 9000, $host = '0.0.0.0')
    {
        try {
            $handler   = new static();
            $processor = new TransmitProcessor($handler);

            $transportFactory = new TTransportFactory();
            $protocolFactory  = new TBinaryProtocolFactory(true, true);

            //作为cli方式运行
            $transport = new TServerSocket($host, $port);
            $server    = new TSimpleServer(
                $processor,
                $transport,
                $transportFactory,
                $transportFactory,
                $protocolFactory,
                $protocolFactory
            );
            var_dump("service start {$host}:{$port}");
            $server->serve();
        } catch (TException $tx) {
            print 'TException: ' . $tx->getMessage() . "\n";
        }
    }
}
