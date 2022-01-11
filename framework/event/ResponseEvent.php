<?php


namespace Framework\event;


use Symfony\Component\HttpFoundation\Response;

class ResponseEvent
{
    protected Response $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }
}