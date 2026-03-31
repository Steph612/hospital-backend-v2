<?php

declare(strict_types=1);

namespace App\PatientAdmission\Domain\Repository;

use App\PatientAdmission\Domain\Entity\PatientAdmission;

interface PatientAdmissionRepositoryInterface
{
    public function save(PatientAdmission $patientAdmission): void;

    public function existsActiveAdmissionForPatient(string $patientId): bool;
}
