<?php

namespace aik27\DromClient\tests;

use aik27\DromClient\Config;
use aik27\DromClient\Http\GuzzleClient;
use aik27\DromClient\Validators\JsonValidator;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public array $config = [
        'urlGetAll' => 'http://example.com/comments',
        'urlCreate' => 'http://example.com/comment',
        'urlUpdate' => 'http://example.com/comment/{id}',
    ];

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    public function testEmptyConfig()
    {
        $this->expectExceptionMessage('$config array can\'t be empty');

        new Config([]);
    }

    public function testEmptyUrlGetAll()
    {
        $this->expectExceptionMessage('"urlGetAll" param is required');

        $config = $this->config;
        unset($config['urlGetAll']);

        new Config($config);
    }

    public function testEmptyUrlPost()
    {
        $this->expectExceptionMessage('"urlCreate" param is required');

        $config = $this->config;
        unset($config['urlCreate']);

        new Config($config);
    }

    public function testEmptyUrlPut()
    {
        $this->expectExceptionMessage('"urlUpdate" param is required');

        $config = $this->config;
        unset($config['urlUpdate']);

        new Config($config);
    }

    public function testMissingIdInUrlPut()
    {
        $this->expectExceptionMessage('"{id}" variable in "urlUpdate" param is required');

        $config = $this->config;
        $config['urlUpdate'] = 'http://example.com/comment';

        new Config($config);
    }

    public function testMissingHttpClient()
    {
        $this->expectExceptionMessage('"httpClient" param is required');

        new Config($this->config);
    }

    public function testHttpClientInstanceof()
    {
        $this->expectExceptionMessage('"httpClient" param is not an object or not implement HttpInterface');

        $config = $this->config;
        $config['httpClient'] = new \ArrayObject();

        new Config($config);
    }

    public function testValidator()
    {
        $this->expectExceptionMessage('"validator" param is not an object or not implement ValidatorInterface');

        $config = $this->config;
        $config['httpClient'] = new GuzzleClient();
        $config['validator'] = new \ArrayObject();

        new Config($config);
    }

    public function testScenarioCreate()
    {
        $this->expectExceptionMessage('"scenarioCreate" param is not an exemplar of Scenario object');

        $config = $this->config;
        $config['httpClient'] = new GuzzleClient();
        $config['scenarioCreate'] = new \ArrayObject();

        new Config($config);
    }

    public function testScenarioUpdate()
    {
        $this->expectExceptionMessage('"scenarioUpdate" param is not an exemplar of Scenario object');

        $config = $this->config;
        $config['httpClient'] = new GuzzleClient();
        $config['scenarioUpdate'] = new \ArrayObject();

        new Config($config);
    }

}