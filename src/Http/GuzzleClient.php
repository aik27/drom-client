<?php

namespace aik27\DromClient\Http;

use aik27\DromClient\Interfaces\HttpInterface;
use GuzzleHttp\Client;

/**
 * Adapter for GuzzleHttp library
 */

class GuzzleClient implements HttpInterface
{
    private array $headers = [];

    /**
     * {@inheritdoc}
     */
    public function request(string $url, string $method = 'GET', array $params = [], array $options = []): string
    {
        if (($method == 'GET' or $method == 'DELETE') and !empty($params)) {
            $options['query'] = $params;
        }

        if ($method == 'POST' or $method == 'PUT') {
            $options['form_params'] = $params;
        }

        $client = new Client();
        $response = $client->request($method, $url, $options);
        $code = $response->getStatusCode();
        $this->headers = $response->getHeaders();

        if ($code !== 200) {
            throw new \Exception('HTTP error. Code: ' . $code . '.');
        }

        return $response->getBody()->getContents();
    }

    /**
     * {@inheritdoc}
     */

    public function getHeaders(): array
    {
        return $this->headers;
    }
}