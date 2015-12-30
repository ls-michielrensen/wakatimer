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

    public function handle($date = 'now', $project = null)
    {
        $commits = $this->wakatimeService->daily($date, $project);
        $commits = array_shift($commits);

        if (empty($commits))
        {
            return false;
        }

        return $this->parseCommits($commits);
    }

    protected function parseCommits($commits)
    {
        $time = [];

        foreach($commits['commits'] as $commit)
        {
            // Try to guess the ticket from the branch name
            $tickets = $this->jiraService->parseTicket($commit['ref']);

            if (empty($tickets))
            {
                // Find tickets in the commit message
                $tickets = $this->jiraService->parseTicket($commit['message']);
            }

            if (!empty($tickets))
            {
                foreach($tickets as $ticket)
                {
                    if (!array_key_exists($ticket, $time))
                    {
                        $time[$ticket] = 0;
                    }

                    $time[$ticket] += $commit['total_seconds'];
                }
            }
        }

        return $time;
    }
}