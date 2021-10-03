<?php

namespace App\Helpers;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface as IResponse;
use Psr\Http\Message\StreamInterface as IStream;

class HttpRequestHelper
{
    private static function createClient()
    {
        return new Client([
            "base_uri" => getenv("API_BASE_URI")
        ]);
    }

    private static function doRequest(string $httpMethod, string $uri, array $headers): ?ResponseMyApi
    {
        $responseData = null;

        self::createClient()
            ->requestAsync($httpMethod, $uri, $headers)
            ->then(
                function (IResponse $response) use (&$responseData) {
                    $responseData = self::convertBody($response->getBody());
                },
                function (RequestException $exception) use (&$responseData) {
                    $response = $exception->getResponse();
                    $responseData = self::convertBody($response->getBody());
                }
            )
            ->wait();

        return $responseData;
    }

    private static function convertBody(IStream $body)
    {
        $contents = json_decode($body->getContents());

        return new ResponseMyApi(
            data: $contents->data,
            statusCode: $contents->statusCode,
            success: $contents->success
        );
    }

    public static function delete(string $uri, array $headers = []): ?ResponseMyApi
    {
        return self::doRequest("DELETE", $uri, $headers);
    }

    public static function get(string $uri, array $headers = []): ?ResponseMyApi
    {
        return self::doRequest("GET", $uri, $headers);
    }

    public static function post(string $uri, array $headers = []): ?ResponseMyApi
    {
        return self::doRequest("POST", $uri, $headers);
    }

    public static function put(string $uri, array $headers = []): ?ResponseMyApi
    {
        return self::doRequest("PUT", $uri, $headers);
    }
}
