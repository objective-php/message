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
     * @return mixed
     */
    public function getParam($param, $default = null, $origin = null);

    /**
     * @return ParameterContainerInterface
     */
    public function getParameters() : ParameterContainerInterface;

    /**
     * @return mixed HTTP method (GET, POST, PUT, DELETE) or CLI
     */
    public function getMethod();

    /**
     * Request route
     *
     * @return mixed
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
