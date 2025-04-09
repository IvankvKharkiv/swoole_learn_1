<?php

require_once __DIR__ . '/vendor/autoload.php';

use Swoole\Http\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;

use FastRoute\RouteCollector;
use App\Router;

$server = new Server("0.0.0.0", 9501, SWOOLE_PROCESS);

$router = new Router();

$server->on("start", function ($server) {
    echo "Swoole server started at http://127.0.0.1:9501\n";
});

// handle all requests with this response
$server->on('request', function (Request $request, Response $response) use ($router) {
    // just to see the errors
    register_shutdown_function(function () use ($response) {
        $error = error_get_last();
        var_dump($error);
        switch ($error['type'] ?? null) {
            case E_ERROR :
            case E_PARSE :
            case E_CORE_ERROR :
            case E_COMPILE_ERROR :
                // log or send:
                // error_log($message);
                // $server->send($fd, $error['message']);
                $response->status(500);
                $response->end($error['message']);
                break;
        }
    });

    // global content type for our responses
    $response->header('Content-Type', 'application/json');

    $result = $router->handleRequest($request);

    $response->end($result);
});

$server->start();