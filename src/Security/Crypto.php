<?php

namespace Restugbk\Security;

use Exception;

class Crypto
{
    private static function getCipher(string $finalKey): string
    {
        $len = strlen(hex2bin($finalKey));
        switch ($len) {
            case 16:
                return 'AES-128-ECB';
            case 24:
                return 'AES-192-ECB';
            case 32:
                return 'AES-256-ECB';
            default:
                throw new Exception("Invalid finalKey length");
        }
    }

    public static function encrypt(string $data, string $finalKey): string
    {
        return base64_encode(
            openssl_encrypt(
                $data,
                self::getCipher($finalKey),
                hex2bin($finalKey),
                OPENSSL_RAW_DATA
            )
        );
    }

    public static function decrypt(string $data, string $finalKey): string
    {
        return openssl_decrypt(
            base64_decode($data),
            self::getCipher($finalKey),
            hex2bin($finalKey),
            OPENSSL_RAW_DATA
        );
    }
}