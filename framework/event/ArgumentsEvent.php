<?php


namespace Framework\event;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class ArgumentsEvent extends Event
{
    protected Request $request;
    protected $arguments;
    protected $controller;

    public function __construct(Request $request, $controller, $arguments)
    {
        $this->request = $request;
        $this->controller = $controller;
        $this->arguments = $arguments;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getArgument(): Request
    {
        return $this->arguments;
    }

}