<?php

namespace App\Controller;

use Swoole\Http\Request;
use Swoole\Http\Response;

class MainPageController implements ControllerInterface
{
    public function invoke(Request $request, Response $response): Response {
        $loader = new \Twig\Loader\FilesystemLoader('./templates');
        $twig = new \Twig\Environment($loader);

        $response->setHeader('Content-Type', 'text/html');

        $response->write($twig->render('demo.html.twig', [
            'page_title' => 'Welcome to My Awesome Site',
            'page_description' => 'This is a simple example using Tailwind CSS and Twig.'
        ]));

        return $response;
    }

}