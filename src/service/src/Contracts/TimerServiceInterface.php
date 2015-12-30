<?php

namespace SEOshop\Service\Contracts;

interface TimerServiceInterface
{
    public function handle($date = 'now', $project = null);
}