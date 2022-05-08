<?php

namespace Root\Yqm\Client;

class YMQClient
{
    private $client;

    public function __construct(string $baseUrl)
    {
        $this->client = new \GuzzleHttp\Client([
            'baseUri' => $baseUrl,
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }
}