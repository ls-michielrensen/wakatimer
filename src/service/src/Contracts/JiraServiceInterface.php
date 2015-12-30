<?php

namespace SEOshop\Service\Contracts;

interface JiraServiceInterface
{
    public function parseTicket($input);

    public function findTicket($ticket);

    public function addWorklog($ticket, $time);
}