<?php

namespace Restugbk\Security;

class Signature
{
    public static function generate(string $timestamp, string $message, string $hmac): string
    {
        return base64_encode(
            hash_hmac(
                'sha256',
                "{$timestamp}-{$message}",
                hex2bin($hmac),
                true
            )
        );
    }
}