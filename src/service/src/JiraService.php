<?php
namespace SEOshop\Service;

use SEOshop\Service\Contracts\JiraServiceInterface;

class JiraService implements JiraServiceInterface
{
    private static $pattern = '([a-zA-Z]{1,}-[0-9]{1,})';

    public function parseTicket($input)
    {
        preg_match_all(self::$pattern, $input, $matches);

        return $matches[0];
    }
}