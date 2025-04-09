<?php

use Swoole\Http\Server;
use Swoole\Runtime;



//const WHEN_ECHO_MODE = 'BEFORE';
const WHEN_ECHO_MODE = 'AFTER';
Runtime::enableCoroutine(true);

$workLoad = function ($second) {
    WHEN_ECHO_MODE === 'BEFORE' ? print $second : 0;
    sleep($second);
    WHEN_ECHO_MODE === 'AFTER' ? print $second : 0;

};

go($workLoad, 3);
go($workLoad, 2);
$workLoad(1);

Swoole\Event::wait();


echo "\n";


