<?php

namespace aik27\DromClient\tests;

use aik27\DromClient\Client;
use aik27\DromClient\Config;
use aik27\DromClient\Http\GuzzleClient;
use aik27\DromClient\Interfaces\HttpInterface;
use aik27\DromClient\Interfaces\ValidatorInterface;
use aik27\DromClient\Validators\JsonValidator;
use PHPUnit\Framework\TestCase;

class GuzzleTest extends TestCase
{
    private Config $config;
    private HttpInterface $httpClient;
    private ValidatorInterface $validator;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->config = new Config([
            'urlGetAll' => 'http://example.com/comments',
            'urlCreate' => 'http://example.com/comment',
            'urlUpdate' => 'http://example.com/comment/{id}',
        ]);
        $this->httpClient = new GuzzleClient();
        $this->validator = new JsonValidator();
        parent::__construct($name, $data, $dataName);
    }

    public function testClientGetAll()
    {
        $this->assertJson('{}');

        $httpClient = $this->createMock(get_class($this->httpClient));
        $httpClient->method('request')
            ->willReturn('{}');

        $client = new Client($this->config, $this->validator, $httpClient);

        return $client->getAll();
    }

    public function testClientPost()
    {
        $this->assertJson('{}');

        $httpClient = $this->createMock(get_class($this->httpClient));
        $httpClient->method('request')
            ->willReturn('{}');

        $client = new Client($this->config, $this->validator, $httpClient);

        return $client->post(['test' => 'test']);
    }

    public function testClientPut()
    {
        $this->assertJson('{}');

        $httpClient = $this->createMock(get_class($this->httpClient));
        $httpClient->method('request')
            ->willReturn('{}');

        $client = new Client($this->config, $this->validator, $httpClient);

        return $client->put([
            'id' => 42,
            'name' => 'Alexandr',
            'message' => 'Hello'
        ]);
    }
}
