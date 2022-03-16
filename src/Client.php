<?php
namespace bingher\transmit;

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TBufferedTransport;
use Thrift\Transport\TSocket;

class Client
{
    protected $server; //连接服务端
    protected $transport; //传输通道

    /**
     * Construct function
     *
     * @param string $host        Server host Default to "127.0.0.1"
     * @param int    $port        Server port Default to 9000
     * @param int    $sendTimeOut Request sendTimeOut Default to 10 seconds
     * @param int    $recvTimeOut Received Timeout Default to 20 seconds
     */
    public function __construct(
        string $host = '127.0.0.1',
        int $port = 9000,
        int $sendTimeOut = 10,
        int $recvTimeOut = 20
    ) {
        $socket = new TSocket($host, $port);
        $socket->setSendTimeout($sendTimeOut * 1000);
        $socket->setRecvTimeout($recvTimeOut * 1000);
        $this->transport = new TBufferedTransport($socket);
        $protocol        = new TBinaryProtocol($this->transport);
        $this->server    = new TransmitClient($protocol);
    }

    /**
     * Magical function __call
     *
     * @param string $name      Name of function
     * @param array  $arguments params array
     *
     * @return string json string
     */
    public function __call($name, $arguments)
    {
        try {
            $this->transport->open();
            $data   = json_encode($arguments[0], JSON_UNESCAPED_UNICODE);
            $result = $this->server->invoke($name, $data);
            return $result;
        } finally {
            if ($this->transport->isOpen()) {
                $this->transport->close();
            }
        }
    }
}
