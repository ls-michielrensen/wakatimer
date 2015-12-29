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
            $return[] = $this->jiraService->parseTicket($commit['message']);
        }

        return $return;
    }
}