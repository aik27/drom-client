<?php

namespace aik27\DromClient\tests;

use aik27\DromClient\Comment;
use aik27\DromClient\Config;
use aik27\DromClient\Http\GuzzleClient;
use aik27\DromClient\Http\SymfonyClient;
use aik27\DromClient\Interfaces\HttpInterface;
use aik27\DromClient\Interfaces\ValidatorInterface;
use aik27\DromClient\Validators\JsonValidator;
use aik27\DromClient\Validators\XmlValidator;
use PHPUnit\Framework\TestCase;

class XmlValidationTest extends TestCase
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
        $this->validator = new XmlValidator();
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

    public function testIncorrectXml()
    {
        $this->expectExceptionMessage('Validation failed');

        $client = $this->createMock(get_class($this->client));
        $client->method('request')
            ->willReturn('<?xml version="1.0" encoding="utf-8"?>
            <!DOCTYPE recipe>
            <recipe name="хлеб" preptime="5min" cooktime="180min">
               <title>
                  Простой хлеб
               </title>');

        $comment = new Comment($this->config, $client, $this->validator);
        $comment->getAll();
    }

    public function testCorrectXml()
    {
        $this->assertString('<?xml version="1.0" encoding="utf-8"?>
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
            </recipe>');

        $client = $this->createMock(get_class($this->client));
        $client->method('request')
            ->willReturn('<?xml version="1.0" encoding="utf-8"?>
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
            </recipe>');

        $comment = new Comment($this->config, $client, $this->validator);
        $comment->getAll();
    }
}
