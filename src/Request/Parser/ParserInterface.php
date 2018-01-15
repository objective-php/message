<?php

namespace ObjectivePHP\Message\Request\Parser;

/**
 * Interface ParserInterface
 * @package ObjectivePHP\Message\Request\Parser
 */
interface ParserInterface
{
    /**
     * @param mixed$data
     * @return array
     */
    public function __invoke($data): array;
}
