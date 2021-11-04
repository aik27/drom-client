<?php

namespace aik27\DromClient;

use aik27\DromClient\Interfaces\ValidatorInterface;

abstract class ClientAbstract
{
    protected const SCENARIO_CREATE = 1;
    protected const SCENARIO_UPDATE = 2;

    protected Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    abstract public function getAll(): string;
    abstract public function create(array $data): string;
    abstract public function update(array $data): string;
}
