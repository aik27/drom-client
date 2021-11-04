<?php

namespace aik27\DromClient;

class Config
{
    protected array $config = [];

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->checkConfig();
    }

    public function get($name): string
    {
        return isset($this->config[$name]) ?? $this->config[$name];
    }

    public function set($name, $value): void
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

        if (!isset($this->config['urlPost']) or empty($this->config['urlPost'])) {
            throw new \Exception('"urlPost" config params required');
        }

        if (!isset($this->config['urlPut']) or empty($this->config['urlPut'])) {
            throw new \Exception('"urlPut" config params required');
        }

        if (!preg_match('#\{id\}#', $this->config['urlPut'])) {
            throw new \Exception('"{id}" variable in "urlPut" is required');
        }
    }
}
