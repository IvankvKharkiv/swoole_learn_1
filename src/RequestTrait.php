<?php

namespace App;

use Swoole\Http\Request;

trait RequestTrait
{
    public function postBodyToArray(Request $request): array
    {
        // form-data and x-www-form-urlencoded work out of the box so we handle JSON POST here
        if ('POST' === $request->server['request_method'] && 'application/json' === $request->header['content-type']) {
            $body = $request->rawContent();
            $post = empty($body) ? [] : json_decode($body);
        } else {
            $post = $request->post ?? [];
        }

        return $post;
    }
}
