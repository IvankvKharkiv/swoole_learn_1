<?php

use Swoole\Http\Server;

$loader = require 'vendor/autoload.php';
$loader->add('App', __DIR__.'/src');

use App\MyClass;

$server = new Server("0.0.0.0", 9501, SWOOLE_PROCESS);

$server->on("start", function ($server) {
    echo "Swoole server started at http://127.0.0.1:9501\n";
});

$server->on("request", function ($request, $response) {
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

//    throw new Exception('some error');

    $tempVar = new MyClass1();
    $int = $tempVar->someField;

    $memory = memory_get_usage();
    $response->header("Content-Type", "text/html");
    $response->end("<h1>$memory</h1><br><h1>$int</h1>");
});

$server->start();