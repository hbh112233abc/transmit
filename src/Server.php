<?php
namespace bingher\transmit;

use Thrift\Exception\TException;
use Thrift\Factory\TBinaryProtocolFactory;
use Thrift\Factory\TTransportFactory;
use Thrift\Server\TServerSocket;
use Thrift\Server\TSimpleServer;

class Server implements TransmitIf
{
    /**
     * Invoke function
     *
     * @param string $func function name
     * @param string $data params json string
     *
     * @return string json string
     */
    public function invoke($func, $data)
    {
        try {
            print_r("call function:" . $func);
            if (!method_exists($this, $func)) {
                throw new \InvalidArgumentException('function not found:' . $func);
            }
            $params = json_decode($data, true);
            print_r("params:");
            print_r($params);
            $data = call_user_func([$this, $func], $params);
            return $this->success($data);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    /**
     * Success return
     *
     * @param mixed  $data result data Default to []
     * @param string $msg  result message Default to "success"
     * @param int    $code result code Default to 0
     *
     * @return string json string
     */
    public function success(
        $data = [],
        string $msg = "success",
        int $code = 0
    ) {
        $result = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
        ];
        print_r("success:");
        print_r($result);
        return json_encode($result);
    }

    /**
     * Error return
     *
     * @param string $msg  result message Default to "error"
     * @param int    $code result code Default to 1
     * @param array  $data result data Default to []
     *
     * @return string json string
     */
    public function error(
        string $msg = "error",
        int $code = 1,
        array $data = []
    ) {
        $result = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
        ];
        print_r("error:");
        print_r($result);
        return json_encode($result);
    }

    /**
     * Run server
     *
     * @param int    $port listen port Default to 9000
     * @param string $host listen ip host
     *
     * @return void
     */
    public static function run(int $port = 9000, string $host = '0.0.0.0')
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
