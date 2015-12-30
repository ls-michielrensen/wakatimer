<?php
namespace SEOshop\Service;

use JiraRestApi\Issue\IssueService;
use JiraRestApi\JiraException;
use SEOshop\Service\Contracts\JiraServiceInterface;

class JiraService implements JiraServiceInterface
{
    private static $pattern = '([a-zA-Z0-9]{1,}-[0-9]{1,})';

    /**
     * @var IssueService $issueService
     */
    protected $issueService;

    public function __construct(IssueService $issueService)
    {
        $this->issueService = $issueService;
    }

    public function parseTicket($input)
    {
        preg_match_all(self::$pattern, $input, $matches);

        return $matches[0];
    }

    public function findTicket($ticket)
    {
        try {
            $issue = $this->issueService->get($ticket);

            return $issue;
        }
        catch(JiraException $e) {
            dd($e->getMessage());
        }
    }
}