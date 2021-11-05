<?php

namespace aik27\DromClient;

use aik27\DromClient\Interfaces\HttpInterface;
use aik27\DromClient\Interfaces\ValidatorInterface;

/**
 * Configure application to work
 *
 * Example:
 *
 *```php
 *  $config = new Config([
 *      'urlGetAll' => 'http://example.com/comments',
 *      'urlCreate' => 'http://example.com/comment',
 *      'urlUpdate' => 'http://example.com/comment/{id}',
 *      'httpClient' => new GuzzleClient(),
 *      'validator' => new JsonValidator(),
 *      'scenarioCreate' => new Scenario([
 *          'name' => [
 *              'type' => 'string',
 *              'required' => true,
 *          ],
 *          'text' => [
 *              'type' => 'string',
 *              'required' => true,
 *          ],
 *      ]),
 *      'scenarioUpdate' => new Scenario([
 *       'id' => [
 *           'type' => 'int',
 *           'required' => true,
 *       ],
 *       'name' => [
 *           'type' => 'string',
 *            'required' => false,
 *       ],
 *       'text' => [
 *           'type' => 'string',
 *           'required' => false,
 *       ],
 *    ]),
 *  ]);
 * ```
 *
 * Available params of config array:
 *
 * ```code
 * + urlGetAll [required] - url to get records from server
 * + urlCreate [required] - url to create record on server
 * + urlUpdate [required] - url to update record on server
 * + httpClient [required] - object of http adapter for external library (Guzzle, Symfony) implements HttpInterface
 * + validator [optional] - object to validate server response implements ValidatorInterface
 * + scenarioCreate [optional] - object providing validation rules to client data on create scenario. Instance of Scenario class.
 * + scenarioUpdate [optional] - object providing validation rules to client data on update scenario. Instance of Scenario class.
 * ```
 */

class Config
{
    protected array $config = [];

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->check();
    }

    public function get(string $name)
    {
        return isset($this->config[$name]) ? $this->config[$name] : '';
    }

    public function set(string $name, $value): void
    {
        $this->config[$name] = $value;
    }

    protected function check(): void
    {
        if (empty($this->config)) {
            throw new \Exception('$config array can\'t be empty');
        }

        if (!isset($this->config['urlGetAll']) or empty($this->config['urlGetAll'])) {
            throw new \Exception('"urlGetAll" param is required');
        }

        if (!isset($this->config['urlCreate']) or empty($this->config['urlCreate'])) {
            throw new \Exception('"urlCreate" param is required');
        }

        if (!isset($this->config['urlUpdate']) or empty($this->config['urlUpdate'])) {
            throw new \Exception('"urlUpdate" param is required');
        }

        if (!preg_match('#\{id\}#', $this->config['urlUpdate'])) {
            throw new \Exception('"{id}" variable in "urlUpdate" param is required');
        }

        if (!isset($this->config['httpClient'])) {
            throw new \Exception('"httpClient" param is required');
        }

        if (!$this->config['httpClient'] instanceof HttpInterface) {
            throw new \Exception('"httpClient" param is not an object or not implement HttpInterface');
        }

        if (isset($this->config['validator']) and !$this->config['validator'] instanceof ValidatorInterface) {
            throw new \Exception('"validator" param is not an object or not implement ValidatorInterface');
        }

        if (isset($this->config['scenarioCreate']) and !$this->config['scenarioCreate'] instanceof Scenario) {
            throw new \Exception('"scenarioCreate" param is not an exemplar of Scenario object');
        }

        if (isset($this->config['scenarioUpdate']) and !$this->config['scenarioUpdate'] instanceof Scenario) {
            throw new \Exception('"scenarioUpdate" param is not an exemplar of Scenario object');
        }
    }
}
