<?php

namespace Framework;

use Framework\event\ArgumentsEvent;
use Framework\event\ControllerEvent;
use Framework\event\RequestEvent;
use Framework\event\ResponseEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

class Simplex
{
    protected EventDispatcherInterface $dispatcher;
    protected UrlMatcherInterface $urlMatcher;
    protected ControllerResolverInterface $controllerResolver;
    protected ArgumentResolverInterface $argumentResolver;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        UrlMatcherInterface $urlMatcher,
        ControllerResolverInterface $controllerResolver,
        ArgumentResolverInterface $argumentResolver )
    {
        $this->dispatcher = $dispatcher;
        $this->urlMatcher = $urlMatcher;
        $this->controllerResolver = $controllerResolver;
        $this->argumentResolver = $argumentResolver;
    }

    public function handle(Request $request)
    {
        $this->urlMatcher->getContext()->fromRequest($request);

        try {
            $request->attributes->add($this->urlMatcher->match($request->getPathInfo()));

            $this->dispatcher->dispatch(new RequestEvent($request), 'kernel.request');

            $controller = $this->controllerResolver->getController($request);

            $this->dispatcher->dispatch(new ControllerEvent($request,$controller), 'kernel.controller');

            $arguments = $this->argumentResolver->getArguments($request, $controller);

            $this->dispatcher->dispatch(new ArgumentsEvent($request,$controller , $arguments), 'kernel.arguments');

            $response = call_user_func_array($controller, $arguments);

            $this->dispatcher->dispatch(new ResponseEvent($response), 'kernel.response');

        } catch (ResourceNotFoundException $e){
            $response = new Response("La page n'existe pas", 404);
        } catch (Exception $e) {
            $response = new Response("Une erreur est survenue", 500);
        }

        return $response;
    }
}