<?php

namespace aik27\DromClient\tests;

use aik27\DromClient\Client;
use aik27\DromClient\Config;
use aik27\DromClient\Http\GuzzleClient;
use aik27\DromClient\Interfaces\HttpInterface;
use aik27\DromClient\Validators\XmlValidator;
use PHPUnit\Framework\TestCase;

class XmlValidationTest extends TestCase
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
            'validator' => new XmlValidator()
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

    public function testIncorrectXml()
    {
        $this->expectExceptionMessage('Response validation failed');

        $httpClient = $this->createMock(get_class($this->client));
        $httpClient->method('request')
            ->willReturn('<?xml version="1.0" encoding="utf-8"?>
            <!DOCTYPE recipe>
            <recipe name="хлеб" preptime="5min" cooktime="180min">
               <title>
                  Простой хлеб
               </title>
             <recipe>');

        $config = $this->config;
        $config['httpClient'] = $httpClient;

        $client = new Client(new Config($config));
        $client->getAll();
    }

    public function testCorrectXml()
    {
        $response = '<?xml version="1.0" encoding="utf-8"?>
            <!DOCTYPE recipe>
            <recipe name="хлеб" preptime="5min" cooktime="180min">
               <title>
                  Простой хлеб
               </title>
               <composition>
                  <ingredient amount="3" unit="стакан">Мука</ingredient>
                  <ingredient amount="0.25" unit="грамм">Дрожжи</ingredient>
                  <ingredient amount="1.5" unit="стакан">Тёплая вода</ingredient>
               </composition>
               <instructions>
                 <step>
                    Смешать все ингредиенты и тщательно замесить. 
                 </step>
                 <step>
                    Закрыть тканью и оставить на один час в тёплом помещении. 
                 </step>
                 <!-- 
                    <step>
                       Почитать вчерашнюю газету. 
                    </step>
                     - это сомнительный шаг...
                  -->
                 <step>
                    Замесить ещё раз, положить на противень и поставить в духовку.
                 </step>
               </instructions>
            </recipe>';

        $httpClient = $this->createMock(get_class($this->client));
        $httpClient->method('request')
            ->willReturn($response);

        $config = $this->config;
        $config['httpClient'] = $httpClient;

        $client = new Client(new Config($config));
        $this->assertSame($response, $client->getAll());
    }
}
