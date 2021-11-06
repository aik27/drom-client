<?php

namespace aik27\DromClient\Interfaces;

/**
 * Specify client adapter for HTTP requests
 */

interface HttpInterface
{
    /**
     * Make HTTP request
     *
     * @param string $method - HTTP method GET | POST | PUT | DELETE | etc.
     * @param string $url
     * @param array $params - GET or POST params in request
     * @param array $options - special options for client. For more information read client documentation
     * @return string
     * @throws \Exception
     */
    public function request(string $url, string $method, array $params = [], array $options = []): string;

    /**
     * Get headers from last response
     *
     * @return array
     */
    public function getHeaders(): array;

}
