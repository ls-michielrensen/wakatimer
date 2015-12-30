<?php

namespace SEOshop\Service\Contracts;

interface TimerServiceInterface
{
    public function handle($date = 'now', $project = null);

    public function exportResults(array $results);
}