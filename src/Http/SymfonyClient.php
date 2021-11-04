<?php

namespace aik27\DromClient\Http;

use aik27\DromClient\Interfaces\HttpInterface;
use Symfony\Component\HttpClient\HttpClient;

class SymfonyClient implements HttpInterface
{
    /**
     * {@inheritdoc}
     */
    public function request(string $url, string $method = 'GET', array $params = [], array $options = []): string
    {
        if (($method == 'GET' or $method == 'DELETE') and !empty($params)) {
            $options['query'] = $params;
        }

        if ($method == 'POST' or $method == 'PUT') {
            $options['body'] = $params;
        }

        $client = HttpClient::create();
        $response = $client->request($method, $url, $options);
        $code = $response->getStatusCode();

        if ($code !== 200) {
            throw new \Exception('HTTP error. Code: ' . $code . '.');
        }

        return $response->getContent();
    }
}