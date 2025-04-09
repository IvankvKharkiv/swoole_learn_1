<?php

namespace App;

use App\Controller\MainPageController;
use App\Request as AppRequest;
use FastRoute\RouteCollector;
use FastRoute;

use Swoole\Http\Request;



class Router
{
    private function get_index_handler(AppRequest $request): string
    {
        $controller = new MainPageController();
        return $controller->invoke($request);
    }

    private function post_index_handler(array $vars): string
    {
        return json_encode([
            'status' => 200,
            'message' => 'Hello world!',
            'vars' => [
                '$_GET' => $vars['request']->get ?? [],
                '$_POST' => $vars['post'],
            ],
        ]);
    }

    private $dispatcher;

    public function __construct()
    {
        $this->dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) {
            $r->addRoute('GET', '/index', 'get_index_handler');
            $r->addRoute('POST', '/index', 'post_index_handler');
        });
    }

    public function handleRequest(Request $request)
    {
        list($code, $handler) = $this->dispatcher->dispatch($request->server['request_method'], $request->server['request_uri']);

        $result = [
            'status' => 404,
            'message' => 'Not Found',
            'errors' => [
                sprintf('The URI "%s" was not found', $request->server['request_uri'])
            ]
        ];

        switch ($code) {
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $result = [
                    'status' => 405,
                    'message' => 'Method Not Allowed',
                    'errors' => [
                        sprintf('Method "%s" is not allowed', $request->server['request_method'])
                    ]
                ];
                break;
            case FastRoute\Dispatcher::FOUND:
                // form-data and x-www-form-urlencoded work out of the box so we handle JSON POST here
                if ($request->server['request_method'] === 'POST' && $request->header['content-type'] === 'application/json') {
                    $body = $request->rawContent();
                    $post = empty($body) ? [] : json_decode($body);
                } else {
                    $post = $request->post ?? [];
                }

                $result = call_user_func([$this, $handler], new AppRequest($request, $post));
                break;
        }

        return $result;
    }


}