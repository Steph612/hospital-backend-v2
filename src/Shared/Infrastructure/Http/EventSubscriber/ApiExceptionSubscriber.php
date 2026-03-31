<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Http\EventSubscriber;

use App\PatientAdmission\Domain\Exception\PatientAlreadyAdmitted;
use App\Shared\Infrastructure\Http\Exception\ApiException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onException',
        ];
    }

    public function onException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof PatientAlreadyAdmitted) {
            $event->setResponse(new JsonResponse(
                ['message' => $exception->getMessage()],
                JsonResponse::HTTP_CONFLICT
            ));

            return;
        }

        if ($exception instanceof \InvalidArgumentException) {
            $event->setResponse(new JsonResponse(
                ['message' => $exception->getMessage()],
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            ));

            return;
        }
    }
}
