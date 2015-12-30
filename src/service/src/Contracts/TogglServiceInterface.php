<?php
namespace SEOshop\Service\Contracts;

interface TogglServiceInterface
{
    public function getTimeEntries();

    public function createTimeEntry(array $entry);
}