<?php

namespace aik27\DromClient\tests;

use aik27\DromClient\Comment;
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
    private HttpInterface $client;
    private ValidatorInterface $validator;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->config = new Config([
            'urlGetAll' => 'http://example.com/comments',
            'urlPost' => 'http://example.com/comment',
            'urlPut' => 'http://example.com/comment/{id}',
        ]);
        $this->client = new GuzzleClient();
        $this->validator = new JsonValidator();
        parent::__construct($name, $data, $dataName);
    }

    public function testPlainText()
    {
        $this->expectExceptionMessage('Validation failed');

        $client = $this->createMock(get_class($this->client));
        $client->method('request')
            ->willReturn('sajhdflksadf');

        $comment = new Comment($this->config, $client, $this->validator);
        $comment->getAll();
    }

    public function testIncorrectJson()
    {
        $this->expectExceptionMessage('Validation failed');

        $client = $this->createMock(get_class($this->client));
        $client->method('request')
            ->willReturn('{"firstName": "Иван","lastName": "Иванов');

        $comment = new Comment($this->config, $client, $this->validator);
        $comment->getAll();
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

        $client = $this->createMock(get_class($this->client));
        $client->method('request')
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

        $comment = new Comment($this->config, $client, $this->validator);
        $comment->getAll();
    }
}
