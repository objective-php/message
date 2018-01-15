<?php

use ObjectivePHP\Message\Request\Parser\JsonParser;

class JsonParserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var JsonParser
     */
    protected $instance;

    public function setUp()
    {
        $this->instance = new JsonParser();
    }

    public function tearDown()
    {
        $this->instance = null;
    }

    public function testInvokeJsonDecodeFailed()
    {
        $fixtureData = 'test';

        $this->assertEquals([], $this->instance->__invoke($fixtureData));
    }

    public function testInvoke()
    {
        $fixtureData = '{"id":1}';

        $this->assertEquals(['id' => 1], $this->instance->__invoke($fixtureData));
    }
}
