<?php

    namespace ObjectivePHP\Message\Request\Parameter\Container;

    use ObjectivePHP\Message\Request\HttpRequest;
    use ObjectivePHP\Message\Request\RequestInterface;
    use ObjectivePHP\Primitives\Collection\Collection;

    /**
     * Class HttpParameterContainer
     *
     * @package ObjectivePHP\Message\Request\Parameter\Container
     */
    class HttpParameterContainer extends AbstractContainer
    {

        /**
         * @var RequestInterface
         */
        protected $request;

        /**
         * @var Collection
         */
        protected $params;

        /**
         * Constructor
         * @param HttpRequest $request
         */
        public function __construct(HttpRequest $request)
        {
            $this->params = new Collection();
            $this->setGet($request->getGet());
            $this->setPost($request->getPost());
            $this->setEnv($_ENV);
            $matchedRoute = $request->getMatchedRoute();
            if($matchedRoute)
            {
                $this->setRoute($matchedRoute->getParams());
            }
            $request->setParameters($this);
        }

        /**
         * @param $getParams
         *
         * @return $this
         * @throws \ObjectivePHP\Primitives\Exception
         */
        public function setGet($getParams)
        {
            $params = Collection::cast($getParams);

            // make params with no values available as anonymous params
            $namedParams   = $params->copy()->filter();
            $unnamedParams = $params->copy()->filter(function ($value)
            {
                return !$value;
            })->flip()
            ;

            $params = $namedParams->merge($unnamedParams);

            $this->params['get'] = $params;

            return $this;
        }

        /**
         * @param null $param
         * @param null $default
         *
         * @return mixed|null
         * @throws \ObjectivePHP\Primitives\Exception
         */
        public function fromGet($param = null, $default = null)
        {

            if (is_null($param))
            {
                return $this->params->get('get');
            }

            return $this->get($param, $default, 'get');
        }

        /**
         * @param        $param
         * @param null   $default
         * @param string $origin
         *
         * @return mixed
         * @throws \ObjectivePHP\Primitives\Exception
         */
        public function get($param, $default = null, $origin = 'get')
        {
            return $this->params->get($origin)->get($param, $default);
        }

        /**
         * @codeAssistHint
         * @return HttpRequest
         */
        public function getRequest()
        {
            return $this->request;
        }

        /**
         * @param null $param
         * @param null $default
         *
         * @return mixed|null
         * @throws \ObjectivePHP\Primitives\Exception
         */
        public function fromPost($param = null, $default = null)
        {
            if (is_null($param))
            {
                return $this->params->get('post');
            }

            return $this->get($param, $default, 'post');
        }

        /**
         * @param null $var
         * @param null $default
         *
         * @return mixed|null
         * @throws \ObjectivePHP\Primitives\Exception
         */
        public function fromEnv($var = null, $default = null)
        {
            if (is_null($var))
            {
                return $this->params->get('env');
            }

            return $this->get($var, $default, 'env');
        }

        /**
         * @param null $file
         * @param null $default
         *
         * @return mixed|null
         * @throws \ObjectivePHP\Primitives\Exception
         */
        public function fromFiles($file = null, $default = null)
        {
            if (is_null($file))
            {
                return $this->params->get('files');
            }

            return $this->get($file, $default, 'files');
        }


        /**
         * @param null $route
         * @param null $default
         *
         * @return mixed|null
         * @throws \ObjectivePHP\Primitives\Exception
         */
        public function fromRoute($route = null, $default = null)
        {
            if (is_null($route))
            {
                return $this->params->get('route');
            }

            return $this->get($route, $default, 'route');
        }



        /**
         * @param $postParams
         */
        public function setPost($postParams)
        {
            $this->params['post'] = Collection::cast($postParams);

            return $this;
        }

        /**
         * @param $files
         */
        public function setFiles($files)
        {
            $this->params['files'] = Collection::cast($files);

            return $this;
        }

        /**
         * @param $envVars
         */
        public function setEnv($envVars)
        {
            $this->params['env'] = Collection::cast($envVars);

            return $this;
        }

        public function setRoute($routeVars)
        {
            $this->params['route'] = Collection::cast($routeVars);

            return $this;
        }

    }