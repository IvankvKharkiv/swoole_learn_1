<?php

namespace App\Controller;

use App\Request;

class MainPageController
{
    public function invoke(Request $request): string {
        return json_encode([
            'status' => 200,
            'message' => 'Hello world!',
            'vars' => [
                '$_GET' => $request->getSwooleRequest()->get,
                '$_POST' => $request->getPostData(),
            ],
        ]);
    }

}