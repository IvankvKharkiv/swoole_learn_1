<?php

namespace App;

//require_once __DIR__ . '/Controller/MainPageController.php';

use App\Controller\ControllerInterface;
use App\Controller\MainPageController;
use FastRoute\RouteCollector;
use FastRoute;

use Swoole\Http\Request;
use Swoole\Http\Response;


class Router
{
    private array $controllerArray;
    private $dispatcher;

    public function __construct()
    {
        $this->dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) {
            $r->addRoute('GET', '/index', MainPageController::class);
            $r->addRoute('POST', '/index', MainPageController::class);
        });

        $files = glob(__DIR__ . '/Controller' . '/*.php');

        foreach ($files as $file) {
            require_once $file;
        }

        $this->controllerArray = [];

        foreach (get_declared_classes() as $className) {
            if (in_array(ControllerInterface::class, class_implements($className))) {
                $this->controllerArray[] = $className;
            }
        }
    }

    private function getControllerResponse(string $handler, Request $request, Response $response): Response
    {
        foreach ($this->controllerArray as $class) {
            if ($class === $handler) {
                $controller = new $handler();
                return $controller->invoke($request, $response);
            }
        }

        $response->setHeader('Content-Type', 'application/json');
        $response->write(json_encode([
            'status' => 404,
            'message' => 'Controller Not Found',
            'errors' => [
                sprintf('The URI "%s" was not found', $request->server['request_uri'])
            ]
        ]));

        return $response;
    }

    public function handleRequest(Request $request, Response $response): Response
    {
        list($code, $handler) = $this->dispatcher->dispatch($request->server['request_method'], $request->server['request_uri']);

        switch ($code) {
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $response->setHeader('Content-Type', 'application/json');
                $response->write(json_encode([
                    'status' => 405,
                    'message' => 'Method Not Allowed',
                    'errors' => [
                        sprintf('Method "%s" is not allowed', $request->server['request_method'])
                    ]
                ]));
                break;
            case FastRoute\Dispatcher::FOUND:
                $response = $this->getControllerResponse($handler, $request, $response);
                break;
            default:
                $response->setHeader('Content-Type', 'application/json');
                $response->write(json_encode([
                    'status' => 404,
                    'message' => 'Not Found',
                    'errors' => [
                        sprintf('The URI "%s" was not found', $request->server['request_uri'])
                    ]
                ]));
        }

        return $response;
    }


}