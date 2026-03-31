<?php

declare(strict_types=1);

namespace App\PatientAdmission\UI\Http;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * DTO d’entrée HTTP
 */
final readonly class CreatePatientAdmissionRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $patientId = '',
        #[Assert\NotBlank]
        #[Assert\Length(max: 20)]
        public string $hospitalUnitCode = '',
        #[Assert\NotBlank]
        public string $admittedAt = '',
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $reason = '',
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $createdBy = '',
    ) {
    }
}
