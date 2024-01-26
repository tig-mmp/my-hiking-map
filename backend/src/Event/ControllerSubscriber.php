<?php

namespace App\Event;

use App\Service\JwtService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ControllerSubscriber implements EventSubscriberInterface
{
    /**
     * @var array
     */
    private $token;

    /**
     * @var string
     */
    private $jwt;

    /**
     * @var JwtService
     */
    private $jwtService;

    public function __construct(RequestStack $requestStack, JwtService $jwtService)
    {
        $request = $requestStack->getCurrentRequest();
        $this->jwt = $request->headers->get("Authorization");
        $this->jwtService = $jwtService;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();
        if (is_array($controller)) {
            $controller = $controller[0];
        }

        if (property_exists($controller, "MIDDLEWARE")) {
            $this->token = $this->jwtService->decodeToken($this->jwt);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => "onKernelController",
        ];
    }
}
