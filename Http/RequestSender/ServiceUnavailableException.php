<?php

namespace Everlution\FileJetBundle\Http\RequestSender;

class ServiceUnavailableException extends \Exception
{
    public function __construct()
    {
        parent::__construct('SERVICE UNAVAILABLE: Could not connect to Storage Manager [https://sm.filejet.io].');
    }
}
