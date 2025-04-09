<?php

namespace App;

class Request
{
    public function __construct(
        private \Swoole\Http\Request $request,
        private array $postData)
    {
    }

    /**
     * @return array
     */
    public function getPostData(): array
    {
        return $this->postData;
    }

    /**
     * @return Swoole\Http\Request
     */
    public function getSwooleRequest(): \Swoole\Http\Request
    {
        return $this->request;
    }

}