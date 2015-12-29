<?php
namespace SEOshop\Service;

use SEOshop\Service\Contracts\JiraServiceInterface;
use SEOshop\Service\Contracts\TimerServiceInterface;
use SEOshop\Service\Contracts\WakatimeServiceInterface;

class TimerService implements TimerServiceInterface
{
    /**
     * @var WakatimeServiceInterface
     */
    protected $wakatimeService;

    /**
     * @var JiraServiceInterface
     */
    protected $jiraService;

    public function __construct(WakatimeServiceInterface $wakatimeService, JiraServiceInterface $jiraService)
    {
        $this->wakatimeService = $wakatimeService;
        $this->jiraService = $jiraService;
    }

    public function handle($project = null)
    {
        $commits = $this->wakatimeService->daily($project);

        return $this->parseCommits(array_shift($commits));
    }

    protected function parseCommits($commits)
    {
        $return = [];

        foreach($commits['commits'] as $commit)
        {
            // Find tickets in the commit message
            $tickets = $this->jiraService->parseTicket($commit['message']);

            if (empty($tickets))
            {
                // Try to guess the ticket from the branch name
                $tickets = $this->jiraService->parseTicket($commit['ref']);
            }

            if (!empty($tickets))
            {
                foreach($tickets as $ticket)
                {
                    $return[$ticket][] = $commit;
                }
            }
        }

        return $return;
    }
}