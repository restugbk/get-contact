<?php

namespace Restugbk;

use Exception;
use Restugbk\Http\HttpClientInterface;
use Restugbk\Http\CurlHttpClient;
use Restugbk\Security\Crypto;
use Restugbk\Security\Signature;
use Restugbk\Validator\NumberValidator;

class GetContact
{
    private string $token;
    private string $finalKey;
    private HttpClientInterface $httpClient;

    public const HMAC = '793167597c4a25263656206b5469243e5f416c69385d2f7843716d4d4d5031242a29493846774a2c2a725f59554d2034683f40372b40233c3e2b772d65335657';
    public const API_URL = '68747470733a2f2f7062737372762d63656e7472616c6576656e74732e636f6d2f76322e382f6e756d6265722d64657461696c';
    public const SEARCH_URL = '68747470733a2f2f7062737372762d63656e7472616c6576656e74732e636f6d2f76322e382f736561726368';

    public function __construct(string $token, string $finalKey, ?HttpClientInterface $httpClient = null)
    {
        if (empty($token)) {
            throw new Exception("Token is required!");
        }
        if (empty($finalKey)) {
            throw new Exception("Final key is required!");
        }

        $this->token = $token;
        $this->finalKey = $finalKey;
        $this->httpClient = $httpClient ?? new CurlHttpClient();
    }

    public function checkNumber(string $number): array
    {
        $number = NumberValidator::validate($number);

        $payload = [
            "countryCode" => "us",
            "phoneNumber" => $number,
            "source"      => "profile",
            "token"       => $this->token
        ];

        $jsonPayload = json_encode($payload, JSON_UNESCAPED_SLASHES);
        $timestamp   = (string) round(microtime(true) * 1000);

        $signature   = Signature::generate($timestamp, $jsonPayload, self::HMAC);
        $encrypted   = Crypto::encrypt($jsonPayload, $this->finalKey);

        $headers = [
            "X-Os: android 9",
            "X-Mobile-Service: GMS",
            "X-App-Version: 5.6.2",
            "X-Client-Device-Id: 63c063f778cc6ee4",
            "X-Lang: en_US",
            "X-Token: {$this->token}",
            "X-Req-Timestamp: {$timestamp}",
            "X-Encrypted: 1",
            "X-Network-Country: us",
            "X-Country-Code: us",
            "X-Req-Signature: {$signature}",
            "Content-Type: application/json"
        ];

        $response = $this->httpClient->post(
            hex2bin(self::API_URL),
            ["data" => $encrypted],
            $headers
        );

        if (!isset($response['data'])) {
            return [
                "success"   => false,
                "message"   => "Invalid response from API",
                "raw"       => $response
            ];
        }

        $decrypted = Crypto::decrypt($response['data'], $this->finalKey);
        $json      = json_decode($decrypted, true);

        return [
            "success" => true,
            "number"  => $number,
            "tags"    => array_map(fn($t) => $t['tag'], $json['result']['tags'] ?? []),
            "raw"     => $json
        ];
    }

    public function searchNumber(string $number): array
    {
        $number = NumberValidator::validate($number);

        $payload = [
            "countryCode" => "us",
            "phoneNumber" => $number,
            "source"      => "search",
            "token"       => $this->token
        ];

        $jsonPayload = json_encode($payload, JSON_UNESCAPED_SLASHES);
        $timestamp   = (string) round(microtime(true) * 1000);

        $signature   = Signature::generate($timestamp, $jsonPayload, self::HMAC);
        $encrypted   = Crypto::encrypt($jsonPayload, $this->finalKey);

        $headers = [
            "X-Os: android 9",
            "X-Mobile-Service: GMS",
            "X-App-Version: 5.6.2",
            "X-Client-Device-Id: 63c063f778cc6ee4",
            "X-Lang: en_US",
            "X-Token: {$this->token}",
            "X-Req-Timestamp: {$timestamp}",
            "X-Encrypted: 1",
            "X-Network-Country: us",
            "X-Country-Code: us",
            "X-Req-Signature: {$signature}",
            "Content-Type: application/json"
        ];

        $response = $this->httpClient->post(
            hex2bin(self::SEARCH_URL),
            ["data" => $encrypted],
            $headers
        );

        if (!isset($response['data'])) {
            return [
                "success"   => false,
                "message"   => "Invalid response from API",
                "raw"       => $response
            ];
        }

        $decrypted = Crypto::decrypt($response['data'], $this->finalKey);
        $json      = json_decode($decrypted, true);

        return [
            "success" => true,
            "number"  => $number,
            "profile" => $json['result']['profile'] ?? '',
            "raw"     => $json
        ];
    }
}
