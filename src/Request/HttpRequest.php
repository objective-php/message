<?php

    namespace ObjectivePHP\Message\Request;

    use ObjectivePHP\Message\Request\Parameter\Container\HttpParameterContainer;
    use ObjectivePHP\Message\Request\Parameter\Container\ParameterContainerInterface;
    use Psr\Http\Message\StreamInterface;
    use Zend\Diactoros\Request;

    class HttpRequest extends Request implements RequestInterface
    {

        /**
         * @var HttpParameterContainer
         */
        protected $parameters;

        /**
         * @param null|string                     $uri     URI for the request, if any.
         * @param null|string                     $method  HTTP method for the request, if any.
         * @param string|resource|StreamInterface $body    Message body, if any.
         * @param array                           $headers Headers for the message, if any.
         *
         * @throws \InvalidArgumentException for any invalid value.
         */
        public function __construct($uri = null, $method = null, $body = 'php://input', array $headers = [])
        {
            parent::__construct($uri, $method, $body, $headers);

        }

        /**
         * Proxy to ParameterContainerInterface::getParam()
         *
         * @param $param    string      Parameter name
         * @param $param    mixed       Default value
         * @param $origin   string       Source name (for instance 'get' for HTTP param)
         *
         * @return ParameterContainerInterface|mixed
         */
        public function getParam($param = null, $default = null, $origin = null)
        {
            $this->getParameters()->get($param, $default, $origin);
        }

        /**
         * @return HttpParameterContainer
         */
        public function getParameters()
        {

            if(is_null($this->parameters))
            {
                // build default parameter container from request
                $this->parameters = new HttpParameterContainer($this);
            }

            return $this->parameters;
        }
    }