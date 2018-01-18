<?php

namespace ObjectivePHP\Message\Request;

use ObjectivePHP\Message\Request\Parser\ParserInterface;
use RuntimeException;
use TypeError;
use ObjectivePHP\Message\Request\Parameter\Container\HttpParameterContainer;
use ObjectivePHP\Message\Request\Parameter\Container\ParameterContainerInterface;
use ObjectivePHP\Message\Request\Parser\JsonParser;
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
     * @var bool|array
     */
    protected $parsedBody = false;

    /**
     * @var array
     */
    protected $bodyParsers = [];

    /**
     * @var array
     */
    protected $bodyParsersList = [
        'application/json' => JsonParser::class,
    ];

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
        $this->loadParsers();
        parent::__construct($uri, $method, $body, $headers);
    }

    /**
     * In charge to load every parser in the attributes bodyParsers
     */
    protected function loadParsers()
    {
        foreach ($this->bodyParsersList as $type => $parser) {
            if (is_subclass_of($parser, ParserInterface::class)) {
                $this->registerMediaTypeParser($type, new $parser());
            }
        }
    }

    /**
     * @param $mediaType
     * @param callable $callable
     */
    public function registerMediaTypeParser($mediaType, callable $callable)
    {
        $this->bodyParsers[(string)$mediaType] = $callable;
    }

    /**
     * @return array|bool|null
     * @throws \TypeError
     */
    public function getParsedBody()
    {
        if ($this->parsedBody !== false) {
            return $this->parsedBody;
        }

        $contentsBody = (string)$this->getBody();
        if (!$contentsBody) {
            return null;
        }

        $mediaType = $this->getHeader('content-type');
        $mediaType = $mediaType[0] ?? null;

        if (empty($mediaType)) {
            throw new RuntimeException('Request media type can not be null');
        }

        if (isset($this->bodyParsers[$mediaType]) === true) {
            $parsed = $this->bodyParsers[$mediaType]($contentsBody);
            if (!is_null($parsed) && !is_object($parsed) && !is_array($parsed)) {
                throw new TypeError(
                    'Request body media type parser return value must be an array, an object, or null'
                );
            }
            $this->parsedBody = $parsed;
            return $this->parsedBody;
        }
        return null;
    }

    /**
     * Proxy to ParameterContainerInterface::getParam()
     *
     * @param $param    string      Parameter name
     * @param $param    mixed       Default value
     * @param $origin   string       Source name (for instance 'get' for HTTP param)
     *
     * @return mixed
     */
    public function getParam($param, $default = null, $origin = null)
    {
        $this->getParameters()->get($param, $default, $origin);
    }

    /**
     * @return HttpParameterContainer
     */
    public function getParameters() : ParameterContainerInterface
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
