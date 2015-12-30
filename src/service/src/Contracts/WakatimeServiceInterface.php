<?php
namespace SEOshop\Service\Contracts;

interface WakatimeServiceInterface
{
    public function projects();
    public function commits($project, $author = null);
    public function daily($date = 'now', $project = null);
}