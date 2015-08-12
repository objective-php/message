<?php

    namespace ObjectivePHP\Message\Request;
    
    
    use ObjectivePHP\Parameter\Container\ParameterContainerInterface;

    abstract class AbstractRequest implements RequestInterface
    {
        /**
         * @var string
         */
        protected $method;

        /**
         * @var ParameterContainerInterface
         */
        protected $parameters;

        /**
         * @return string
         */
        public function getMethod()
        {
            return $this->method;
        }

        /**
         * @param string $method
         *
         * @return $this
         */
        public function setMethod($method)
        {
            $this->method = $method;

            return $this;
        }

        /**
         * @param ParameterContainerInterface $parameters
         *
         * @return mixed
         */
        public function setParameters(ParameterContainerInterface $parameters)
        {
            $this->parameters = $parameters;

            return $this;
        }

        /**
         * @return ParameterContainerInterface
         */
        public function getParameters()
        {
            return $this->parameters;
        }


    }