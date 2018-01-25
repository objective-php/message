<?php

namespace ObjectivePHP\Message\Request;

use ObjectivePHP\Message\Request\Parameter\Container\HttpParameterContainer;
use ObjectivePHP\Message\Request\Parameter\Container\ParameterContainerInterface;
use ObjectivePHP\Router\MatchedRoute;
use Psr\Http\Message\StreamInterface;
use Zend\Diactoros\ServerRequest;

/**
 * Class HttpRequest
 *
 * @package ObjectivePHP\Message\Request
 */
class HttpRequest extends ServerRequest implements RequestInterface
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
     * @param null|string $uri URI for the request, if any.
     * @param null|string $method HTTP method for the request, if any.
     * @param string|resource|StreamInterface $body Message body, if any.
     * @param array $headers Headers for the message, if any.
     * @param array $serverParams Server parameters, typically from $_SERVER
     * @param array $uploadedFiles Upload file information, a tree of UploadedFiles
     * @param array $cookies Cookies for the message, if any.
     * @param array $queryParams Query params for the message, if any.
     * @param null|array|object $parsedBody The deserialized body parameters, if any.
     * @param string $protocol HTTP protocol version.
     *
     * @throws \InvalidArgumentException for any invalid value.
     */
    public function __construct(
        $uri = null,
        $method = null,
        $body = 'php://input',
        array $headers = [],
        array $serverParams = [],
        array $uploadedFiles = [],
        array $cookies = [],
        array $queryParams = [],
        $parsedBody = null,
        $protocol = '1.1'
    ) {
        parent::__construct(
            $serverParams,
            $uploadedFiles,
            $uri,
            $method,
            $body,
            $headers,
            $cookies,
            $queryParams,
            $parsedBody,
            $protocol
        );
    }

    /**
     * Proxy to ParameterContainerInterface::getParam()
     *
     * @param      $param    mixed  Default value
     * @param null $default
     * @param      $origin   string Source name (for instance 'get' for HTTP param)
     *
     * @return mixed
     */
    public function getParam($param, $default = null, $origin = null)
    {
        return $this->getParameters()->get($param, $default, $origin);
    }

    /**
     * @return HttpParameterContainer
     */
    public function getParameters(): ParameterContainerInterface
    {

        if (is_null($this->parameters)) {
            // build default parameter container from request
            $this->parameters = new HttpParameterContainer($this);
        }

        return $this->parameters;
    }

    /**
     * @param ParameterContainerInterface $parameters
     *
     * @return HttpRequest
     */
    public function setParameters(ParameterContainerInterface $parameters)
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
     *
     * @return HttpRequest
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
     *
     * @return HttpRequest
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
     *
     * @return HttpRequest
     */
    public function setMatchedRoute(MatchedRoute $matchedRoute)
    {
        $this->matchedRoute = $matchedRoute;

        return $this;
    }
}
