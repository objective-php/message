<?php

namespace ObjectivePHP\Message\Request\Parser;

/**
 * Class JsonParser
 * @package ObjectivePHP\Message\Request\Parser
 */
class JsonParser implements ParserInterface
{
    /**
     * @param mixed $data
     * @return array
     */
    public function __invoke($data): array
    {
        $parsed = json_decode($data, true);
        return !is_array($parsed) ? [] : $parsed;
    }
}
