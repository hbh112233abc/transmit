<?php
namespace bingher\transmit;

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TBufferedTransport;
use Thrift\Transport\TSocket;

class Client
{
    protected $server;    //连接服务端
    protected $transport; //传输通道

    /**
     * Construct function
     *
     * @param string $host        Server host Default to "127.0.0.1"
     * @param int    $port        Server port Default to 9000
     * @param int    $sendTimeOut Request sendTimeOut Default to 10 seconds
     * @param int    $recvTimeOut Received Timeout Default to 60 seconds
     */
    public function __construct(
        string $host = '127.0.0.1',
        int $port = 9000,
        int $sendTimeOut = 10,
        int $recvTimeOut = 60
    ) {
        $socket = new TSocket($host, $port);
        $socket->setSendTimeout($sendTimeOut * 1000);
        $socket->setRecvTimeout($recvTimeOut * 1000);
        $this->transport = new TBufferedTransport($socket);
        $protocol        = new TBinaryProtocol($this->transport);
        $this->server    = new TransmitClient($protocol);
    }

    /**
     * 请求调用
     *
     * @param string $name      方法名
     * @param array  $arguments 传参参数
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        try {
            $this->transport->open();
            $data   = json_encode($arguments[0], JSON_UNESCAPED_UNICODE);
            $result = $this->server->invoke($name, $data);
            /** @var array{code:int,msg:string,data:mixed} $res*/
            $res = json_decode($result, true);
            if ($res['code'] !== 0) {
                throw new \Exception($res['msg']);
            }
            return $res['data'];
        } finally {
            if ($this->transport->isOpen()) {
                $this->transport->close();
            }
        }
    }
}
