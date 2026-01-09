<?php

namespace Restugbk\Http;

use Exception;

class CurlHttpClient implements HttpClientInterface
{
    public function post(string $url, array $body, array $headers): array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => json_encode($body),
            CURLOPT_TIMEOUT => 30
        ]);

        $res = curl_exec($ch);
        if ($err = curl_error($ch)) {
            curl_close($ch);
            throw new Exception($err);
        }
        curl_close($ch);

        return json_decode($res, true) ?? [];
    }
}