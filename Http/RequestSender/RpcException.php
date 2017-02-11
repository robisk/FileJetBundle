<?php

namespace Everlution\FileJetBundle\Http\RequestSender;

use Everlution\FileJetBundle\Http\Response\Response;

class RpcException extends \Exception
{
    /** @var Response */
    protected $response;

    /**
     * @param string $message
     * @param Response $response
     */
    public function __construct($message, Response $response)
    {
        parent::__construct($message, $response->getStatusCode());
        $this->response = $response;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
