<?php

namespace SEOshop\Service;

use Carbon\Carbon;
use Mabasic\WakaTime\WakaTime;
use SEOshop\Service\Contracts\WakatimeServiceInterface;

class WakatimeService implements WakatimeServiceInterface
{
    /**
     * @var WakaTime $client
     */
    protected $client;

    public function __construct(WakaTime $client)
    {
        $this->client = $client;
    }

    public function projects($project = null)
    {
        if ($project !== null)
        {
            return $this->client->project($project);
        }

        return $this->client->projects();
    }

    public function commits($project, $author = null)
    {
        return $this->client->commits($project, $author);
    }

    public function daily($date = 'now', $project = null)
    {
        $return = [];
        $checkpoint = Carbon::createFromTimestamp(strtotime($date));

        if ($project !== null)
        {
            $project = $this->projects($project);

            $return[] = $this->getCommitsByProject($project['data'], $checkpoint);
        }
        else
        {
            $projects = $this->projects();

            foreach($projects['data'] as $project)
            {
                if ($project['repository'] !== null)
                {
                    $return[] = $this->getCommitsByProject($project, $checkpoint);
                }
            }
        }

        return $return;
    }

    protected function getCommitsByProject($project, $checkpoint)
    {
        $commits = [];
        $checkpoint = Carbon::createFromTimestamp(strtotime($checkpoint));
        $lastSyncedAt = Carbon::createFromTimestamp(strtotime($project['repository']['last_synced_at']));

        if ($lastSyncedAt->isSameDay($checkpoint))
        {
            $commits = $this->commits($project['id']);
        }

        return $commits;
    }
}