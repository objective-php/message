<?php

    namespace ObjectivePHP\Message\Request;
    
    

    use ObjectivePHP\Message\Request\Parameter\Container\ParameterContainerInterface;
    use ObjectivePHP\Router\MatchedRoute;
    /**
     * Interface RequestInterface
     *
     * @package ObjectivePHP\Message\Request
     */
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
         * @return ParameterContainerInterface
         */
        public function getParameters();

        /**
         * @return mixed HTTP method (GET, POST, PUT, DELETE) or CLI
         *
         * @deprecated
         */
        public function getMethod();

        /**
         * Request route
         *
         * @return mixed
         *
         * @deprecated
         */
        public function getRoute();

        /**
         * @param $route
         *
         * @return mixed
         */
        public function setRoute($route);

        /**
         * @param MatchedRoute $matchedRoute
         * @return mixed
         */
        public function setMatchedRoute(MatchedRoute $matchedRoute);

        /**
         * @return MatchedRoute
         */
        public function getMatchedRoute();

    }