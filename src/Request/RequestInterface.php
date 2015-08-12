<?php

    namespace ObjectivePHP\Message\Request;
    
    
    use ObjectivePHP\Parameter\Container\ParameterContainerInterface;

    interface RequestInterface
    {
        /**
         * Proxy to ParameterContainerInterface::getParam()
         *
         * @param $param    string      Parameter name
         * @param $param    mixed       Default value
         * @param $origin  string       Source name (for instance 'get' for HTTP param)
         * @return ParameterContainerInterface|mixed
         */
        public function getParam($param = null, $default = null, $origin = null);

        /**
         * @return mixed HTTP method (GET, POST, PUT, DELETE) or CLI
         */
        public function getMethod();

    }