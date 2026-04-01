<?php

declare(strict_types=1);

namespace App\PatientAdmission\Application\Create\EventHandler;

use App\PatientAdmission\Application\Create\Event\PatientAdmissionCreated;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class PatientAdmissionCreatedHandler
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(PatientAdmissionCreated $event): void
    {
        // Exemple simple (remplaçable plus tard)
        // Ici tu peux brancher :
        // - logging métier
        // - audit
        // - notification
        // - intégration externe

        /*error_log(sprintf(
            '[PatientAdmissionCreated] admissionId=%s patientId=%s createdAt=%s',
            $event->admissionId,
            $event->patientId,
            $event->createdAt
        ));*/
        $this->logger->info('[PatientAdmissionCreated]', [
            'admissionId' => $event->admissionId,
            'patientId' => $event->patientId,
            'createdAt' => $event->createdAt,
        ]);
    }
}
