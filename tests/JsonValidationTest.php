<?php

namespace aik27\DromClient\tests;

use aik27\DromClient\Client;
use aik27\DromClient\Config;
use aik27\DromClient\Http\GuzzleClient;
use aik27\DromClient\Interfaces\HttpInterface;
use aik27\DromClient\Validators\JsonValidator;
use PHPUnit\Framework\TestCase;

class JsonValidationTest extends TestCase
{
    private array $config;
    private HttpInterface $client;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->config = [
            'urlGetAll' => 'http://example.com/comments',
            'urlCreate' => 'http://example.com/comment',
            'urlUpdate' => 'http://example.com/comment/{id}',
            'httpClient' => new GuzzleClient(),
            'validator' => new JsonValidator(),
        ];
        $this->client = new GuzzleClient();
        parent::__construct($name, $data, $dataName);
    }

    public function testPlainText()
    {
        $this->expectExceptionMessage('Response validation failed');

        $httpClient = $this->createMock(get_class($this->client));
        $httpClient->method('request')
            ->willReturn('sajhdflksadf');

        $config = $this->config;
        $config['httpClient'] = $httpClient;

        $client = new Client(new Config($config));
        $client->getAll();
    }

    public function testIncorrectJson()
    {
        $this->expectExceptionMessage('Response validation failed');

        $httpClient = $this->createMock(get_class($this->client));
        $httpClient->method('request')
            ->willReturn('{"firstName": "Иван","lastName": "Иванов');

        $config = $this->config;
        $config['httpClient'] = $httpClient;

        $client = new Client(new Config($config));
        $client->getAll();
    }

    public function testCorrectJson()
    {
        $response = '{
               "firstName": "Иван",
               "lastName": "Иванов",
               "address": {
                   "streetAddress": "Московское ш., 101, кв.101",
                   "city": "Ленинград",
                   "postalCode": 101101
               },
               "phoneNumbers": [
                   "812 123-1234",
                   "916 123-4567"
               ]
            }';
        $this->assertJson($response);

        $httpClient = $this->createMock(get_class($this->client));
        $httpClient->method('request')
            ->willReturn($response);

        $config = $this->config;
        $config['httpClient'] = $httpClient;

        $client = new Client(new Config($config));
        $client->getAll();
    }
}
