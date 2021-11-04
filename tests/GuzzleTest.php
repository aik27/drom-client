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

class GuzzleTest extends TestCase
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

    public function testClientGetAll()
    {
        $this->assertJson('{}');

        $client = $this->createMock(get_class($this->client));
        $client->method('request')
            ->willReturn('{}');

        $comment = new Comment($this->config, $client, $this->validator);
        return $comment->getAll();
    }

    public function testClientPost()
    {
        $this->assertJson('{}');

        $client = $this->createMock(get_class($this->client));
        $client->method('request')
            ->willReturn('{}');

        $comment = new Comment($this->config, $client, $this->validator);
        return $comment->post(['test' => 'test']);
    }

    public function testClientPut()
    {
        $this->assertJson('{}');

        $client = $this->createMock(get_class($this->client));
        $client->method('request')
            ->willReturn('{}');

        $comment = new Comment($this->config, $client, $this->validator);
        return $comment->put([
            'id' => 42,
            'name' => 'Alexandr',
            'message' => 'Hello'
        ]);
    }
}
