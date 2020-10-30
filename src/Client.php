<?php
namespace bingher\transmit;

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TBufferedTransport;
use Thrift\Transport\TSocket;

class Client
{
    protected $server; //连接服务端
    protected $transport; //传输通道
    public function __construct(string $host = '127.0.0.1', int $port = 9000, int $sendTimeOut = 10, int $recvTimeOut = 20)
    {
        $socket = new TSocket($host, $port);
        $socket->setSendTimeout($sendTimeOut * 1000);
        $socket->setRecvTimeout($recvTimeOut * 1000);
        $this->transport = new TBufferedTransport($socket);
        $protocol        = new TBinaryProtocol($this->transport);
        $this->server    = new TransmitClient($protocol);
    }

    public function __call($name, $arguments)
    {
        $this->transport->open();
        $data   = json_encode($arguments[0], JSON_UNESCAPED_UNICODE);
        $result = $this->server->invoke($name, $data);
        $this->transport->close();
        return $result;
    }
}
