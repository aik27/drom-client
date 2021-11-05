<?php

namespace aik27\DromClient;

use aik27\DromClient\Interfaces\HttpInterface;
use aik27\DromClient\Interfaces\ValidatorInterface;

class Config
{
    protected array $config = [];

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->check();
    }

    public function get(string $name)
    {
        return isset($this->config[$name]) ? $this->config[$name] : '';
    }

    public function set(string $name, $value): void
    {
        $this->config[$name] = $value;
    }

    protected function check(): void
    {
        if (empty($this->config)) {
            throw new \Exception('$config array can\'t be empty');
        }

        if (!isset($this->config['urlGetAll']) or empty($this->config['urlGetAll'])) {
            throw new \Exception('"urlGetAll" param is required');
        }

        if (!isset($this->config['urlCreate']) or empty($this->config['urlCreate'])) {
            throw new \Exception('"urlCreate" param is required');
        }

        if (!isset($this->config['urlUpdate']) or empty($this->config['urlUpdate'])) {
            throw new \Exception('"urlUpdate" param is required');
        }

        if (!preg_match('#\{id\}#', $this->config['urlUpdate'])) {
            throw new \Exception('"{id}" variable in "urlUpdate" param is required');
        }

        if (!isset($this->config['httpClient'])) {
            throw new \Exception('"httpClient" param is required');
        }

        if (!$this->config['httpClient'] instanceof HttpInterface) {
            throw new \Exception('"httpClient" param is not an object or not implement HttpInterface');
        }

        if (isset($this->config['validator']) and !$this->config['validator'] instanceof ValidatorInterface) {
            throw new \Exception('"validator" param is not an object or not implement ValidatorInterface');
        }

        if (isset($this->config['scenarioCreate']) and !$this->config['scenarioCreate'] instanceof Scenario) {
            throw new \Exception('"scenarioCreate" param is not an exemplar of Scenario object');
        }

        if (isset($this->config['scenarioUpdate']) and !$this->config['scenarioUpdate'] instanceof Scenario) {
            throw new \Exception('"scenarioUpdate" param is not an exemplar of Scenario object');
        }
    }
}
