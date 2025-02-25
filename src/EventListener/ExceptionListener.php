<?php

namespace App\EventListener;

use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof EntityNotFoundException) {
            $response = new JsonResponse(['message' => $exception->getMessage()], JsonResponse::HTTP_NOT_FOUND);
        } elseif ($exception instanceof HttpExceptionInterface) {
            $response = new JsonResponse(['message' => $exception->getMessage()], $exception->getStatusCode());
        } else {
            $response = new JsonResponse(['message' => 'Internal Server Error'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        $event->setResponse($response);
    }
}
