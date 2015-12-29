<?php

namespace SEOshop\Service;

use Mabasic\WakaTime\WakaTime;
use SEOshop\Service\Contracts\JiraServiceInterface;
use SEOshop\Service\Contracts\WakatimeServiceInterface;

class WakatimeService implements WakatimeServiceInterface
{
    /**
     * @var WakaTime $client
     */
    protected $client;

    /**
     * @var JiraServiceInterface $jiraService
     */
    protected $jiraService;

    public function __construct($client, JiraServiceInterface $jiraService)
    {
        $this->client = $client;
        $this->jiraService = $jiraService;
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

        if ($project !== null) {
            $return[] = $this->commits($project);
        }
        else {
            $projects = $this->projects();

            foreach($projects['data'] as $project){
                if ($project['repository'] !== null) {
                    $return[] =  $this->commits($project['id']);
                }
            }
        }

        return $return;
    }
}