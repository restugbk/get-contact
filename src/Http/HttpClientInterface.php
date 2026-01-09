<?php

namespace Restugbk\Http;

interface HttpClientInterface
{
    public function post(string $url, array $body, array $headers): array;
}