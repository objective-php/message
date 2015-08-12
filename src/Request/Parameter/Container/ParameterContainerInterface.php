<?php
    
    namespace ObjectivePHP\Message\Request\Parameter\Container;

    use ObjectivePHP\Message\Request\RequestInterface;

    interface ParameterContainerInterface
    {

        /**
         * @param      $param
         * @param null $default
         * @param null $origin
         *
         * @return mixed
         */
        public function get($param, $default = null, $origin = null);

        /**
         * @param RequestInterface $request
         *
         * @return mixed
         */
        public function setRequest(RequestInterface $request);

        /**
         * @return RequestInterface
         */
        public function getRequest();

    }