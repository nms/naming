<?php

namespace nms\naming\server;

use think\swoole\Server;
use think\facade\Env;

use think\swoole\Application;
use think\Loader;
use think\Facade;

use think\swoole\Cookie;
use think\swoole\Session;

class Swoole extends Server
{
    protected $host = 'trunk.thinkphp.com';
    protected $port = 9501;
    protected $serverType = 'socket';
    protected $sockType = SWOOLE_SOCK_TCP;
    protected $mode = SWOOLE_PROCESS;

    protected $app;
    protected $option = [
        'worker_num'=> 4,
        'backlog'	=> 128,
    ];

    public function onStart($server)
    {
        echo "server: onStart success" . PHP_EOL;
    }

    public function onWorkerStart($server, $worker_id)
    {
        $this->app = new Application();
        Loader::addClassMap([
            'think\\log\\driver\\File' => __DIR__ . '/log/File.php',
        ]);

        $this->app->swoole = $server;

        Facade::bind([
            'think\facade\Cookie'     => Cookie::class,
            'think\facade\Session'    => Session::class,
            facade\Application::class => Application::class,
            facade\Http::class        => Http::class,
        ]);

        $this->app->initialize();

        $this->app->bindTo([
            'cookie'  => Cookie::class,
            'session' => Session::class,
        ]);
    }
    public function onConnect($server, $fd, $reactorId)
    {
        echo 'onConnect' . PHP_EOL;
    }

    public function onOpen($server, $request)
    {
        echo "server: handshake success with fd{$request->fd}" . PHP_EOL;
    }

    public function onMessage($server, $frame)
    {
        echo "receive from {$frame->fd}:{$frame->data}, opcode:{$frame->opcode}, fin:{$frame->finish}" . PHP_EOL;
    }

    public function onRequest($request, $response)
    {
        $this->app->swoole($request, $response);
    }

    public function onClose($ser, $fd) {
        echo "client {$fd} closed\n";
    }
}