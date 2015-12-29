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
        return $this->client->projects();
    }

    public function commits($project, $author = null)
    {
        return $this->client->commits($project, $author);
    }

    public function daily($project = null)
    {
        $return = [];

        if ($project !== null)
        {
            $return[] = $this->commits($project);
        }
        else
        {
            $projects = $this->projects();

            foreach($projects['data'] as $project)
            {
                if ($project['repository'] !== null)
                {
                    $lastSyncedAt = Carbon::createFromTimestampUTC($project['repository']['last_synced_at']);
                    if ($lastSyncedAt->isToday())
                    {
                        $return[] = $this->commits($project['id']);
                    }
                }
            }
        }

        return $return;
    }
}