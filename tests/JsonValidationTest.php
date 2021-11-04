<?php

namespace aik27\DromClient\tests;

use aik27\DromClient\Client;
use aik27\DromClient\Config;
use aik27\DromClient\Http\GuzzleClient;
use aik27\DromClient\Http\SymfonyClient;
use aik27\DromClient\Interfaces\HttpInterface;
use aik27\DromClient\Interfaces\ValidatorInterface;
use aik27\DromClient\Validators\JsonValidator;
use PHPUnit\Framework\TestCase;

class JsonValidationTest extends TestCase
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
        $this->client = new GuzzleClient();
        $this->validator = new JsonValidator();
        parent::__construct($name, $data, $dataName);
    }

    public function testPlainText()
    {
        $this->expectExceptionMessage('Validation failed');

        $httpClient = $this->createMock(get_class($this->client));
        $httpClient->method('request')
            ->willReturn('sajhdflksadf');

        $client = new Client($this->config, $httpClient, $this->validator);
        $client->getAll();
    }

    public function testIncorrectJson()
    {
        $this->expectExceptionMessage('Validation failed');

        $httpClient = $this->createMock(get_class($this->client));
        $httpClient->method('request')
            ->willReturn('{"firstName": "Иван","lastName": "Иванов');

        $client = new Client($this->config, $httpClient, $this->validator);
        $client->getAll();
    }

    public function testCorrectJson()
    {
        $this->assertJson('{
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
            }');

        $httpClient = $this->createMock(get_class($this->client));
        $httpClient->method('request')
            ->willReturn('{
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
            }');

        $client = new Client($this->config, $httpClient, $this->validator);
        $client->getAll();
    }
}
