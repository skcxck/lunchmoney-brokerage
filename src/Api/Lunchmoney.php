<?php

namespace Skcxck\Lunchmoney\Api;

use GuzzleHttp\Client;
use InvalidArgumentException;

/**
 * Class Lunchmoney
 * @package Skcxck\Lunchmoney\Api
 */
class Lunchmoney
{
    protected const API_VERSION = 'v1';

    protected Client $client;

    public function __construct()
    {
        if (($token = env('LUNCHMONEY_TOKEN')) === null) {
            throw new InvalidArgumentException('Please provide an authorization token.');
        }

        $this->client = new Client([
            'base_uri' => env('LUNCHMONEY_URI', 'https://dev.lunchmoney.app'),
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ]
        ]);
    }

    /**
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAllAssets(): ?array
    {
        $response = $this->client
            ->request('GET', '/' . static::API_VERSION . '/assets')
            ->getBody()
            ->getContents();

        $decoded = json_decode($response, true);

        return $decoded['assets'] ?? null;
    }

    /**
     * @param array $asset
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function updateAsset(array $asset)
    {
        $response = $this->client
            ->request('PUT', '/' . static::API_VERSION . '/assets/' . $asset['id'], ['form_params' => $asset])
            ->getBody()
            ->getContents();

        return json_decode($response, true);
    }

}