<?php

namespace aik27\DromClient;

use aik27\DromClient\Interfaces\HttpInterface;
use aik27\DromClient\Interfaces\ValidatorInterface;

class Client
{
    protected Config $config;
    protected Utils $utils;
    protected HttpInterface $http;

    protected const SCENARIO_CREATE = 1;
    protected const SCENARIO_UPDATE = 2;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->http = $this->config->get('httpClient');
        $this->utils = new Utils();
    }

    public function getAll(): string
    {
        $response = $this->http->request($this->config->get('urlGetAll'));

        $this->validateResponse($response);

        return $response;
    }

    public function create(object $data): string
    {
        $data = $this->utils->objectToArray($data);

        $this->validateData($data, self::SCENARIO_CREATE);

        $response = $this->http->request($this->config->get('urlCreate'), 'POST', $data);

        $this->validateResponse($response);

        return $response;
    }

    public function update(object $data): string
    {
        $data = $this->utils->objectToArray($data);

        $this->validateData($data, self::SCENARIO_UPDATE);

        $url = str_replace('{id}', $data['id'], $this->config->get('urlUpdate'));
        $response = $this->http->request($url, 'PUT', $data);

        $this->validateResponse($response);

        return $response;
    }

    protected function validateData(array $data, int $scenario): void
    {
        switch ($scenario) {
            case self::SCENARIO_CREATE:
                /* @var Scenario */
                $schema = $this->config->get('scenarioCreate');
                break;
            case self::SCENARIO_UPDATE:
                /* @var Scenario */
                $schema = $this->config->get('scenarioUpdate');
                break;
            default:
                $schema = '';
        }

        if ($schema instanceof Scenario) {
            $schema->checkFieldsDifference($data);
            $schema->checkData($data);
        }
    }

    protected function validateResponse(string $response): void
    {
        /* @var ValidatorInterface */
        $validator = $this->config->get('validator');

        if ($validator instanceof ValidatorInterface) {
            if (!$validator->validate($response)) {
                throw new \Exception('Validation failed');
            }
        }
    }
}
