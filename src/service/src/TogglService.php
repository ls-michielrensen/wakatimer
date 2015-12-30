<?php

namespace SEOshop\Service;

use AJT\Toggl\TogglClient;
use Carbon\Carbon;
use SEOshop\Service\Contracts\TogglServiceInterface;

class TogglService implements TogglServiceInterface
{
    /**
     * @var TogglClient $client
     */
    protected $client;

    public function __construct(TogglClient $client)
    {
        $this->client = $client;
    }

    public function getTimeEntries()
    {
        return $this->client->getTimeEntries();
    }

    public function createTimeEntry(array $entry)
    {
        return $this->client->createTimeEntry($entry);
    }


}