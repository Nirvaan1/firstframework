<?php


namespace Framework\event;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class ControllerEvent extends Event
{
    protected Request $request;
    protected $controller;

    public function __construct(Request $request, $controller)
    {
        $this->request = $request;
        $this->controller = $controller;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}