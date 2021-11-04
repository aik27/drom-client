<?php

namespace aik27\DromClient;

use aik27\DromClient\Http\GuzzleClient;
use aik27\DromClient\Interfaces\HttpInterface;
use aik27\DromClient\Interfaces\ValidatorInterface;

class Client extends ClientAbstract
{
    protected HttpInterface $http;

    public function __construct(Config $config)
    {
        parent::__construct($config);

        $http = $this->config->get('httpClient');

        $this->http = $http instanceof HttpInterface ? $http : new GuzzleClient();
    }

    public function getAll(): string
    {
        $response = $this->http->request($this->config->get('urlGetAll'));

        $this->validateResponse($response);

        return $response;
    }

    public function create(array $data): string
    {
        $this->validateData($data, self::SCENARIO_CREATE);

        $response = $this->http->request($this->config->get('urlCreate'), 'POST', $data);

        $this->validateResponse($response);

        return $response;
    }

    public function update(array $data): string
    {
        $this->validateData($data, self::SCENARIO_UPDATE);

        $url = str_replace('{id}', $data['id'], $this->config->get('urlUpdate'));
        $response = $this->http->request($url, 'PUT', $data);

        $this->validateResponse($response);

        return $response;
    }

    protected function validateData(array $data, int $scenario)
    {
        /* @var Schema */
        $schema = $this->config->get('schema');

        if ($schema instanceof Schema) {
            if ($schema->isDiffent($data, $scenario)) {
                throw new \Exception('Data fields list is different of Schema list');
            }
            $schema->checkFields($data, $scenario);
        }
    }

    protected function validateResponse($response)
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
