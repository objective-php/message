<?php

use ObjectivePHP\Message\Request\HttpRequest;
use ObjectivePHP\Message\Request\Parameter\Container\HttpParameterContainer;
use ObjectivePHP\Message\Request\Parser\JsonParser;
use ObjectivePHP\Message\Request\Parser\ParserInterface;

class HttpRequestTest extends PHPUnit_Framework_TestCase
{
    /** @var HttpRequest */
    protected $instance;

    public function setUp()
    {
        $this->instance =  $this->instance = $this->getMockBuilder(HttpRequest::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
    }

    public function tearDown()
    {
        $this->instance = null;
    }

    public function testGetParametersContainerReturnsDefaultContainer()
    {
        $request = new HttpRequest();

        $this->assertInstanceOf(HttpParameterContainer::class, $request->getParameters());
    }

    public function testBodyParsersList()
    {
        $expectedList = [
            'application/json' => JsonParser::class
        ];

        $propertyBodyParsersList = new ReflectionProperty($this->instance, 'bodyParsersList');
        $propertyBodyParsersList->setAccessible(true);

        $this->assertEquals($expectedList, $propertyBodyParsersList->getValue($this->instance));
    }

    public function testRegisterMediaTypeParser()
    {
        $this->instance = $this->getMockBuilder(HttpRequest::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
        $fixtureMediaType = 'application/json';

        $fixtureCallable = function() {
            return true;
        };

        $expectedBodyParsers = [
            'application/json' => $fixtureCallable,
        ];

        $this->instance->registerMediaTypeParser($fixtureMediaType, $fixtureCallable);

        $propertyBodyParsers = new ReflectionProperty($this->instance, 'bodyParsers');
        $propertyBodyParsers->setAccessible(true);

        $this->assertEquals($expectedBodyParsers, $propertyBodyParsers->getValue($this->instance));
    }

    public function testLoadParsers()
    {
        $bodyParsersList = [
            'application/json' => JsonParser::class,
            'application/xml' => stdClass::class
        ];

        $this->instance = $this->getMockBuilder(HttpRequest::class)
            ->disableOriginalConstructor()
            ->setMethods(['registerMediaTypeParser'])
            ->getMock();

        $propertyBodyParsersList = new ReflectionProperty($this->instance, 'bodyParsersList');
        $propertyBodyParsersList->setAccessible(true);
        $propertyBodyParsersList->setValue($this->instance, $bodyParsersList);

        $this->instance->expects($this->once())
            ->method('registerMediaTypeParser');

        $method = new ReflectionMethod($this->instance, 'loadParsers');
        $method->setAccessible(true);
        $method->invoke($this->instance);
    }

    public function testGetParsedBodyExisting()
    {
        $fixtureBodyParsed = [];

        $propertyBodyParsed = new ReflectionProperty($this->instance, 'parsedBody');
        $propertyBodyParsed->setAccessible(true);
        $propertyBodyParsed->setValue($this->instance, $fixtureBodyParsed);

        $this->assertEquals($fixtureBodyParsed, $this->instance->getParsedBody());
    }

    public function testGetParsedBodyNoBody()
    {
        $this->instance = $this->getMockBuilder(HttpRequest::class)
            ->disableOriginalConstructor()
            ->setMethods(['getBody'])
            ->getMock();

        $this->instance->expects($this->once())
            ->method('getBody')
            ->willReturn(null);

        $this->assertNull($this->instance->getParsedBody());
    }

    public function testGetParsedBodyNoMediaType()
    {
        $this->instance = $this->getMockBuilder(HttpRequest::class)
            ->disableOriginalConstructor()
            ->setMethods(['getBody', 'getHeader'])
            ->getMock();

        $this->instance->expects($this->once())
            ->method('getBody')
            ->willReturn('body');

        $this->instance->expects($this->once())
            ->method('getHeader')
            ->with('content-type')
            ->willReturn([]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Request media type can not be null');

        $this->instance->getParsedBody();
    }

    public function testGetParsedBodyBadParsing()
    {
        $fixtureMediaType = 'application/json';
        $fixtureBody = 'body';

        $mockParser = $this->getMockBuilder(stdClass::class)
            ->setMethods(['__invoke'])
            ->getMock();

        $mockParser->expects($this->once())
            ->method('__invoke')
            ->with($fixtureBody)
            ->willReturn('');

        $this->instance = $this->getMockBuilder(HttpRequest::class)
            ->disableOriginalConstructor()
            ->setMethods(['getBody', 'getHeader'])
            ->getMock();

        $this->instance->expects($this->once())
            ->method('getBody')
            ->willReturn($fixtureBody);

        $this->instance->expects($this->once())
            ->method('getHeader')
            ->with('content-type')
            ->willReturn([$fixtureMediaType]);

        $propertyBodyParsers = new ReflectionProperty($this->instance, 'bodyParsers');
        $propertyBodyParsers->setAccessible(true);
        $propertyBodyParsers->setValue($this->instance, ['application/json' => $mockParser]);

        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('Request body media type parser return value must be an array, an object, or null');

        $this->instance->getParsedBody();
    }

    public function testGetParsedBodyMediaTypeNotExistingInParsers()
    {
        $fixtureMediaType = 'application/json';
        $fixtureBody = 'body';

        $this->instance = $this->getMockBuilder(HttpRequest::class)
            ->disableOriginalConstructor()
            ->setMethods(['getBody', 'getHeader'])
            ->getMock();

        $this->instance->expects($this->once())
            ->method('getBody')
            ->willReturn($fixtureBody);

        $this->instance->expects($this->once())
            ->method('getHeader')
            ->with('content-type')
            ->willReturn([$fixtureMediaType]);

        $propertyBodyParsers = new ReflectionProperty($this->instance, 'bodyParsers');
        $propertyBodyParsers->setAccessible(true);
        $propertyBodyParsers->setValue($this->instance, []);

        $this->assertNull($this->instance->getParsedBody());
    }

    public function testGetParsedBody()
    {
        $fixtureMediaType = 'application/json';
        $fixtureBody = 'body';

        $mockParser = $this->getMockBuilder(stdClass::class)
            ->setMethods(['__invoke'])
            ->getMock();

        $mockParser->expects($this->once())
            ->method('__invoke')
            ->with($fixtureBody)
            ->willReturn([]);

        $this->instance = $this->getMockBuilder(HttpRequest::class)
            ->disableOriginalConstructor()
            ->setMethods(['getBody', 'getHeader'])
            ->getMock();

        $this->instance->expects($this->once())
            ->method('getBody')
            ->willReturn($fixtureBody);

        $this->instance->expects($this->once())
            ->method('getHeader')
            ->with('content-type')
            ->willReturn([$fixtureMediaType]);

        $propertyBodyParsers = new ReflectionProperty($this->instance, 'bodyParsers');
        $propertyBodyParsers->setAccessible(true);
        $propertyBodyParsers->setValue($this->instance, ['application/json' => $mockParser]);

        $this->assertEquals([], $this->instance->getParsedBody());

        $propertyBodyParsed = new ReflectionProperty($this->instance, 'parsedBody');
        $propertyBodyParsed->setAccessible(true);
        $this->assertEquals([], $propertyBodyParsed->getValue($this->instance));
    }
}
