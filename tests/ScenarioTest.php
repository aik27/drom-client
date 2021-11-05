<?php

namespace aik27\DromClient\tests;

use aik27\DromClient\Client;
use aik27\DromClient\Config;
use aik27\DromClient\Http\GuzzleClient;
use aik27\DromClient\Interfaces\HttpInterface;
use aik27\DromClient\Scenario;
use aik27\DromClient\Validators\JsonValidator;
use PHPUnit\Framework\TestCase;

class ScenarioTest extends TestCase
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
            'validator' => new JsonValidator()
        ];
        $this->client = new GuzzleClient();
        parent::__construct($name, $data, $dataName);
    }

    public function testMustBeString()
    {
        $this->expectExceptionMessage('name field must be a string');

        $config = $this->config;
        $config['scenarioCreate'] = new Scenario([
            'name' => [
                'type' => 'string',
                'required' => true,
            ],
            'text' => [
                'type' => 'string',
                'required' => true,
            ],
        ]);
        $client = new Client(new Config($config));
        $client->create((object)[
            'name' => 123,
            'text' => 'Hello world',
        ]);
    }

    public function testMustBeInteger()
    {
        $this->expectExceptionMessage('id field must be an integer');

        $config = $this->config;
        $config['scenarioUpdate'] = new Scenario([
            'id' => [
                'type' => 'int',
                'required' => true,
            ],
            'name' => [
                'type' => 'string',
                'required' => false,
            ],
            'text' => [
                'type' => 'string',
                'required' => false,
            ],
        ]);
        $client = new Client(new Config($config));
        $client->update((object)[
            'id' => '42',
            'name' => 'Alexandr',
            'text' => 'Hello world',
        ]);
    }

    public function testMissingType()
    {
        $this->expectExceptionMessage('Type is required for field id');

        $config = $this->config;
        $config['scenarioUpdate'] = new Scenario([
            'id' => [
                'required' => true,
            ],
            'name' => [
                'type' => 'string',
                'required' => false,
            ],
            'text' => [
                'type' => 'string',
                'required' => false,
            ],
        ]);
        $client = new Client(new Config($config));
        $client->update((object)[
            'id' => 42,
            'name' => 'Alexandr',
            'text' => 'Hello world',
        ]);
    }

    public function testUnsupportedType()
    {
        $this->expectExceptionMessage('Unsupported type for field id');

        $config = $this->config;
        $config['scenarioUpdate'] = new Scenario([
            'id' => [
                'type' => 'object',
                'required' => true,
            ],
            'name' => [
                'type' => 'string',
                'required' => false,
            ],
            'text' => [
                'type' => 'string',
                'required' => false,
            ],
        ]);
        $client = new Client(new Config($config));
        $client->update((object)[
            'id' => 42,
            'name' => 'Alexandr',
            'text' => 'Hello world',
        ]);
    }

    public function testMissingRequiredField()
    {
        $this->expectExceptionMessage('name field is required');

        $config = $this->config;
        $config['scenarioCreate'] = new Scenario([
            'name' => [
                'type' => 'string',
                'required' => true,
            ],
            'text' => [
                'type' => 'string',
                'required' => true,
            ],
        ]);
        $client = new Client(new Config($config));
        $client->create((object)[
            'text' => 'Hello world',
        ]);
    }

    public function testAddExtraField()
    {
        $this->expectExceptionMessage('Field(s) not present in active Scenario: email');

        $config = $this->config;
        $config['scenarioCreate'] = new Scenario([
            'name' => [
                'type' => 'string',
                'required' => true,
            ],
            'text' => [
                'type' => 'string',
                'required' => true,
            ],
        ]);
        $client = new Client(new Config($config));
        $client->create((object)[
            'name' => 'Alexandr',
            'text' => 'Hello world',
            'email' => 'test@example.com'
        ]);
    }

    public function testCreateSuccess()
    {
        $httpClient = $this->createMock(get_class($this->client));
        $httpClient->method('request')
            ->willReturn('{}');

        $config = $this->config;
        $config['httpClient'] = $httpClient;
        $config['scenarioCreate'] = new Scenario([
            'name' => [
                'type' => 'string',
                'required' => true,
            ],
            'text' => [
                'type' => 'string',
                'required' => true,
            ],
        ]);

        $client = new Client(new Config($config));
        $response = $client->create((object)[
            'name' => 'Alexandr',
            'text' => 'Hello world',
        ]);

        $this->assertSame('{}', $response);
    }

    public function testUpdateSuccess()
    {
        $httpClient = $this->createMock(get_class($this->client));
        $httpClient->method('request')
            ->willReturn('{}');

        $config = $this->config;
        $config['httpClient'] = $httpClient;
        $config['scenarioUpdate'] = new Scenario([
            'id' => [
                'type' => 'int',
                'required' => true,
            ],
            'name' => [
                'type' => 'string',
                'required' => false,
            ],
            'text' => [
                'type' => 'string',
                'required' => false,
            ],
        ]);

        $client = new Client(new Config($config));
        $response = $client->update((object)[
            'id' => 42,
            'name' => 'Alexandr',
        ]);

        $this->assertSame('{}', $response);
    }

    public function testCreateWithoutScenario()
    {
        $httpClient = $this->createMock(get_class($this->client));
        $httpClient->method('request')
            ->willReturn('{}');

        $config = $this->config;
        $config['httpClient'] = $httpClient;

        $client = new Client(new Config($config));
        $response = $client->create((object)[
            'name' => 'Alexandr',
            'text' => 'Hello world',
        ]);

        $this->assertSame('{}', $response);
    }

    public function testUpdateWithoutScenario()
    {
        $httpClient = $this->createMock(get_class($this->client));
        $httpClient->method('request')
            ->willReturn('{}');

        $config = $this->config;
        $config['httpClient'] = $httpClient;

        $client = new Client(new Config($config));
        $response = $client->create((object)[
            'id' => 42,
            'name' => 'Alexandr',
            'text' => 'Hello world',
        ]);

        $this->assertSame('{}', $response);
    }

}
