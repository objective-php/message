<?php
    

    use ObjectivePHP\Message\Request\HttpRequest;
    use ObjectivePHP\Message\Request\Parameter\Container\HttpParameterContainer;

    class HttpRequestTest extends PHPUnit_Framework_TestCase
    {


        public function testGetParametersContainerReturnsDefaultContainer()
        {
            $request = new HttpRequest();

            $this->assertInstanceOf(HttpParameterContainer::class, $request->getParameters());
        }

    }