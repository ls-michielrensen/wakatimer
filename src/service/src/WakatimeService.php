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

    public function daily($date = 'today', $project = null)
    {
        $results = [];
        $checkpoint = Carbon::parse($date);

        if ($project !== null)
        {

            $project = $this->projects($project);

            $results[$project['data']['repository']['name']] = $this->getCommitsByProject($project['data'], $checkpoint);
        }
        else
        {
            $projects = $this->projects();

            foreach($projects['data'] as $project)
            {
                if ($project['repository'] !== null)
                {
                    $results[$project['repository']['name']] = $this->getCommitsByProject($project, $checkpoint);
                }
            }
        }

        return $results;
    }

    protected function getCommitsByProject($project, $checkpoint)
    {
        $results = [];
        $commits = $this->commits($project['id']);

        foreach($commits['commits'] as $commit)
        {
            $authorDate = Carbon::parse($commit['author_date']);

            if ($authorDate->isSameDay($checkpoint))
            {
                $results[] = $commit;
            }
        }

        return $results;
    }
}