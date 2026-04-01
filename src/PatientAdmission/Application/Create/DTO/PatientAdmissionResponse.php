<?php

declare(strict_types=1);

namespace App\PatientAdmission\Application\Create\DTO;

/**
 * DTO de sortie applicatif
 */
final readonly class PatientAdmissionResponse
{
    public function __construct(
        public string $id,
        public string $status,
        public string $patientId,
        public string $hospitalUnitCode,
        public string $admittedAt,
        public string $reason,
        public string $createdBy,
        public string $createdAt,
    ) {
    }
}
