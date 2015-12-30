<?php
namespace SEOshop\Service;

use Carbon\Carbon;
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

    public function handle($date = 'now', $project = null)
    {
        $commits = $this->wakatimeService->daily($date, $project);
        $commits = array_shift($commits);

        if (empty($commits))
        {
            return 'No commits or tickets in this timeperiod (or for this project)';
        }

        return $this->parseCommits($commits);
    }

    public function exportResults(array $results)
    {
        foreach($results as $result)
        {
            $totalSeconds = (int) $result['commit']['total_seconds'];
            $start = Carbon::createFromTimestamp(strtotime($result['commit']['author_date']));

            if ($totalSeconds > 0)
            {
                $this->togglService->createTimeEntry([
                    'time_entry' => [
                        'description' => $result['commit']['message'],
                        'start' => $start->subSeconds($totalSeconds)->format('c'),
                        'duration' => $totalSeconds,
                        'pid' => 0,
                        'wid' => env('TOGGL_DEFAULT_WORKSPACE'),
                        'created_with' => 'wakatimer'
                    ]
                ]);
            }
        }
    }

    protected function parseCommits($commits)
    {
        $entries = [];

        foreach($commits['commits'] as $commit)
        {
            // Try to guess the ticket from the branch name
            $tickets = $this->jiraService->parseTicket($commit['ref']);

            if (empty($tickets))
            {
                // Find tickets in the commit message
                $tickets = $this->jiraService->parseTicket($commit['message']);
            }

            $entries[] = [
                'commit' => $commit,
                'tickets' => implode(', ', $tickets)
            ];
        }

        return $entries;
    }
}