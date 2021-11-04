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
            'urlPost' => 'http://example.com/comment',
            'urlPut' => 'http://example.com/comment/{id}',
        ]);
    }

    public function testEmptyUrlPost()
    {
        $this->expectExceptionMessage('"urlPost" config params required');
        new Config([
            'urlGetAll' => 'http://example.com/comments',
            'urlPut' => 'http://example.com/comment/{id}',
        ]);
    }

    public function testEmptyUrlPut()
    {
        $this->expectExceptionMessage('"urlPut" config params required');
        new Config([
           'urlGetAll' => 'http://example.com/comments',
           'urlPost' => 'http://example.com/comment',
        ]);
    }

    public function testMissingIdInUrlPut()
    {
        $this->expectExceptionMessage('"{id}" variable in "urlPut" is required');
        new Config([
           'urlGetAll' => 'http://example.com/comments',
           'urlPost' => 'http://example.com/comment',
           'urlPut' => 'http://example.com/comment',
        ]);
    }
}