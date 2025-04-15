<?php

namespace App\Controller;

use Swoole\Http\Request;
use Swoole\Http\Response;

interface ControllerInterface
{
    public function invoke(Request $request, Response $response): Response;

}