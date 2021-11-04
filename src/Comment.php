<?php

namespace aik27\DromClient;

class Comment extends ServiceAbstract
{
    public function getAll(): string
    {
        $response = $this->http->request($this->config->get('urlGetAll'));

        if (!$this->validator->validate($response)) {
            throw new \Exception('Validation failed');
        }

        return $response;
    }

    public function post(array $data): string
    {
        $response = $this->http->request($this->config->get('urlPost'), 'POST', $data);

        if (!$this->validator->validate($response)) {
            throw new \Exception('Validation failed');
        }

        return $response;
    }

    public function put(array $data): string
    {
        if (!isset($data['id']) or empty($data['id'])) {
            throw new \Exception('"id" is required for PUT request');
        }

        $url = str_replace('{id}', $data['id'], $this->config->get('urlPut'));

        $response = $this->http->request($url, 'PUT', $data);

        if (!$this->validator->validate($response)) {
            throw new \Exception('Validation failed');
        }

        return $response;
    }
}
