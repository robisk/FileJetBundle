<?php

namespace Everlution\FileJetBundle\Http\RequestSender;

use Everlution\FileJetBundle\Http\Response\Response;
use Everlution\FileJetBundle\Http\Request\Request;

interface RequestSender
{
    /**
     * @param Request $request
     * @return Response
     */
    public function send(Request $request);
}
