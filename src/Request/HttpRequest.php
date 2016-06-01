<?php

    namespace ObjectivePHP\Message\Request;

    use ObjectivePHP\Message\Request\Parameter\Container\HttpParameterContainer;
    use ObjectivePHP\Message\Request\Parameter\Container\ParameterContainerInterface;
    use ObjectivePHP\Router\MatchedRoute;
    use Psr\Http\Message\StreamInterface;
    use Zend\Diactoros\Request;

    /**
     * Class HttpRequest
     *
     * @package ObjectivePHP\Message\Request
     */
    class HttpRequest extends Request implements RequestInterface
    {

        /**
         * @var HttpParameterContainer
         */
        protected $parameters;

        /**
         * @var string
         */
        protected $route;

        /**
         * @var mixed
         */
        protected $action;

        /**
         * @var MatchedRoute
         */
        protected $matchedRoute;

        /**
         * @var
         */
        protected $get = [];

        /**
         * @var array
         */
        protected $post = [];

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

            if (is_null($this->parameters))
            {
                // build default parameter container from request
                $this->parameters = new HttpParameterContainer($this);
            }

            return $this->parameters;
        }

        /**
         * @param HttpParameterContainer $parameters
         */
        public function setParameters(HttpParameterContainer $parameters)
        {
            $this->parameters = $parameters;

            return $this;
        }



        /**
         * @return string
         */
        public function getRoute()
        {
            return $this->route;
        }

        /**
         * @param string $route
         *
         * @return $this
         */
        public function setRoute($route)
        {
            $this->route = $route;

            return $this;
        }

        /**
         * @return mixed
         */
        public function getAction()
        {
            return $this->action;
        }

        /**
         * @param mixed $action
         */
        public function setAction($action)
        {
            $this->action = $action;
        }

        /**
         * @return mixed
         */
        public function getGet()
        {
            return $this->get;
        }

        /**
         * @param mixed $get
         */
        public function setGet(array $get)
        {
            $this->get = $get;

            return $this;
        }

        /**
         * @return array
         */
        public function getPost()
        {
            return $this->post;
        }

        /**
         * @param array $post
         */
        public function setPost(array $post)
        {
            $this->post = $post;

            return $this;
        }

        /**
         * @return mixed
         */
        public function getMatchedRoute()
        {
            return $this->matchedRoute;
        }

        /**
         * @param mixed $matchedRoute
         */
        public function setMatchedRoute(MatchedRoute $matchedRoute)
        {
            $this->matchedRoute = $matchedRoute;

            return $this;
        }

    }