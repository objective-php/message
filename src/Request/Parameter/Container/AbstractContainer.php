<?php

    namespace ObjectivePHP\Message\Request\Parameter\Container;
    
    
    use ObjectivePHP\Message\Request\RequestInterface;

    abstract class AbstractContainer implements ParameterContainerInterface
    {
        /**
         * @var RequestInterface
         */
        protected $request;

        /**
         * @return RequestInterface
         */
        public function getRequest()
        {
            return $this->request;
        }

        /**
         * @param RequestInterface $request
         *
         * @return $this
         */
        public function setRequest(RequestInterface $request)
        {
            $this->request = $request;

            return $this;
        }


    }