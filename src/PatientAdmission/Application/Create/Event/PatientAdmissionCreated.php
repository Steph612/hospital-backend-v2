<?php

declare(strict_types=1);

namespace App\PatientAdmission\Application\Create\Event;

final readonly class PatientAdmissionCreated
{
    public function __construct(
        public string $admissionId,
        public string $patientId,
        public string $createdAt,
    ) {
    }
}
