<?php

declare(strict_types=1);

namespace App\PatientAdmission\Application\Create\Command;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Command applicatif
 */
final readonly class CreatePatientAdmissionCommand
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $patientId,
        #[Assert\NotBlank]
        #[Assert\Length(max: 20)]
        public string $hospitalUnitCode,
        #[Assert\NotBlank]
        public string $admittedAt,
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $reason,
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $createdBy,
    ) {
    }
}
