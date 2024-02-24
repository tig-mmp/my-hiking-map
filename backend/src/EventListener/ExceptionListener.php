<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $errorCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
        $parameters = $exception instanceof HttpExceptionInterface ? $exception->getHeaders() : null;

        $responseMessage = ["msg_code" => $exception->getMessage()];
        if ($parameters && is_array($parameters)) {
            $responseMessage = array_merge($responseMessage, $parameters);
        }

        $response = new JsonResponse($responseMessage, $errorCode);
        $event->setResponse($response);
    }
}
