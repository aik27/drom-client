<?php

namespace aik27\DromClient;

use aik27\DromClient\Interfaces\HttpInterface;
use aik27\DromClient\Interfaces\ValidatorInterface;

abstract class ServiceAbstract
{
    protected Config $config;
    protected HttpInterface $http;
    protected ValidatorInterface $validator;

    public function __construct(Config $config, HttpInterface $httpClient, ValidatorInterface $validator)
    {
        $this->http = $httpClient;
        $this->validator = $validator;
        $this->config = $config;
        $this->config->checkConfig();
    }

    abstract public function getAll(): string;
    abstract public function post(array $data): string;
    abstract public function put(array $data): string;
}
