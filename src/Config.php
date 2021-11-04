<?php

namespace aik27\DromClient;

use aik27\DromClient\Interfaces\ValidatorInterface;

class Config
{
    protected array $config = [];

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->checkConfig();
    }

    public function get(string $name)
    {
        return isset($this->config[$name]) ? $this->config[$name] : '';
    }

    public function set(string $name, $value): void
    {
        $this->config[$name] = $value;
    }

    public function checkConfig(): void
    {
        if (empty($this->config)) {
            throw new \Exception('$config array can\'t be empty');
        }

        if (!isset($this->config['urlGetAll']) or empty($this->config['urlGetAll'])) {
            throw new \Exception('"urlGetAll" config params required');
        }

        if (!isset($this->config['urlCreate']) or empty($this->config['urlCreate'])) {
            throw new \Exception('"urlCreate" config params required');
        }

        if (!isset($this->config['urlUpdate']) or empty($this->config['urlUpdate'])) {
            throw new \Exception('"urlUpdate" config params required');
        }

        if (!preg_match('#\{id\}#', $this->config['urlUpdate'])) {
            throw new \Exception('"{id}" variable in "urlUpdate" is required');
        }

    }
}
