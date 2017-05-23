<?php

    namespace Test\ObjectivePHP\Message\Request\Parameter\Container;

    use ObjectivePHP\Message\Request\HttpRequest;
    use ObjectivePHP\Message\Request\Parameter\Container\HttpParameterContainer;
    use ObjectivePHP\PHPUnit\TestCase;
    
    class HttpParameterContainerTest extends TestCase
    {
        public function testParametersAreExtractedFromEnvironment()
        {
            $GET['param1'] = 'value';
            $GET['param0 value'] = '';


            $request = $this->getMockBuilder(HttpRequest::class)->getMock();
            $request->method('getGet')->willReturn($GET);

            $container = new HttpParameterContainer($request);

            $this->assertEquals('value', $container->get('param1'));
            $this->assertEquals('param0 value', $container->get(0));

        }

        public function testGetParameterEqualToZero()
        {
            $GET['param1'] = '0';

            $request = $this->getMockBuilder(HttpRequest::class)->getMock();
            $request->method('getGet')->willReturn($GET);

            $container = new HttpParameterContainer($request);

            $this->assertSame('0', $container->get('param1'));

        }

    }
