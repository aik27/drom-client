<?php

namespace aik27\DromClient\tests;

use aik27\DromClient\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testEmptyConfig()
    {
        $this->expectExceptionMessage('$config array can\'t be empty');
        new Config([]);
    }

    public function testEmptyUrlGetAll()
    {
        $this->expectExceptionMessage('"urlGetAll" config params required');
        new Config([
            'urlCreate' => 'http://example.com/comment',
            'urlUpdate' => 'http://example.com/comment/{id}',
        ]);
    }

    public function testEmptyUrlPost()
    {
        $this->expectExceptionMessage('"urlCreate" config params required');
        new Config([
            'urlGetAll' => 'http://example.com/comments',
            'urlUpdate' => 'http://example.com/comment/{id}',
        ]);
    }

    public function testEmptyUrlPut()
    {
        $this->expectExceptionMessage('"urlUpdate" config params required');
        new Config([
           'urlGetAll' => 'http://example.com/comments',
           'urlCreate' => 'http://example.com/comment',
        ]);
    }

    public function testMissingIdInUrlPut()
    {
        $this->expectExceptionMessage('"{id}" variable in "urlUpdate" is required');
        new Config([
           'urlGetAll' => 'http://example.com/comments',
           'urlCreate' => 'http://example.com/comment',
           'urlUpdate' => 'http://example.com/comment',
        ]);
    }
}