<?php

declare(strict_types=1);

namespace App\PatientAdmission\Domain\Exception;

final class PatientAlreadyAdmitted extends \DomainException
{
    public static function forPatient(string $patientId): self
    {
        return new self(sprintf('Patient "%s" already has an active admission.', $patientId));
    }
}
