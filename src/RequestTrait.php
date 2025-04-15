<?php

namespace App;

use Swoole\Http\Request;

trait RequestTrait
{
    public function postBodyToArray(Request $request): array
    {
        // form-data and x-www-form-urlencoded work out of the box so we handle JSON POST here
        if ($request->server['request_method'] === 'POST' && $request->header['content-type'] === 'application/json') {
            $body = $request->rawContent();
            $post = empty($body) ? [] : json_decode($body);
        } else {
            $post = $request->post ?? [];
        }

        return $post;
    }

}