<?php
namespace SEOshop\Service;

use Carbon\Carbon;
use Exception;
use SEOshop\Service\Contracts\JiraServiceInterface;
use SEOshop\Service\Contracts\TimerServiceInterface;
use SEOshop\Service\Contracts\TogglServiceInterface;
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

    /**
     * @var TogglServiceInterface
     */
    protected $togglService;

    public function __construct(WakatimeServiceInterface $wakatimeService, JiraServiceInterface $jiraService, TogglServiceInterface $togglService)
    {
        $this->wakatimeService = $wakatimeService;
        $this->jiraService = $jiraService;
        $this->togglService = $togglService;
    }

    public function handle($date = 'today', $project = null)
    {
        $commits = $this->wakatimeService->daily($date, $project);

        if (empty($commits))
        {
            throw new Exception('No commits or tickets in this timeperiod (or for this project)');
        }

        return $this->parseCommits($commits);
    }

    public function exportResults(array $results)
    {
        foreach($results as $result)
        {
            $totalSeconds = (int) $result['commit']['total_seconds'];
            $start = Carbon::parse($result['commit']['author_date']);

            if ($totalSeconds > 0)
            {
                $this->togglService->createTimeEntry([
                    'time_entry' => [
                        'description' => $result['commit']['message'],
                        'start' => $start->subSeconds($totalSeconds)->format('c'),
                        'duration' => $totalSeconds,
                        'pid' => 0,
                        'wid' => (int) env('TOGGL_DEFAULT_WORKSPACE'),
                        'created_with' => 'wakatimer'
                    ]
                ]);
            }
        }
    }

    protected function parseCommits($results)
    {
        $entries = [];

        foreach($results as $project => $commits)
        {
            if (empty($commits))
            {
                continue;
            }

            $entries[] = [
                'project' => $project,
                'commits' => $commits
            ];
        }

        return $entries;
    }
}