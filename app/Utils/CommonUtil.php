<?php

namespace App\Utils;
class CommonUtil
{
    public static function buildClientUrl($uri, $params = []): string
    {
        $baseUri = env('CLIENT_URL');
        $baseUri = rtrim($baseUri, '/') . '/';
        if(!$params || count($params)) {
            return $baseUri . ltrim($uri, '/');
        }

        return $baseUri . ltrim($uri, '/')  . '?' . http_build_query($params);
    }
}
