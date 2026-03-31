<?php

declare(strict_types=1);

namespace App\PatientAdmission\Application\Create;

use App\PatientAdmission\Application\Create\CreatePatientAdmissionCommand;
use App\PatientAdmission\Application\Create\PatientAdmissionResponse;
use App\PatientAdmission\Domain\Entity\PatientAdmission;
use App\PatientAdmission\Domain\Exception\PatientAlreadyAdmitted;
use App\PatientAdmission\Domain\Repository\PatientAdmissionRepositoryInterface;

final readonly class CreatePatientAdmissionHandler
{
    public function __construct(
        private PatientAdmissionRepositoryInterface $patientAdmissionRepository,
    ) {
    }

    public function handle(CreatePatientAdmissionCommand $command): PatientAdmissionResponse
    {
        if ($this->patientAdmissionRepository->existsActiveAdmissionForPatient($command->patientId)) {
            throw PatientAlreadyAdmitted::forPatient($command->patientId);
        }

        try {
            $admittedAt = new \DateTimeImmutable($command->admittedAt);
        } catch (\Throwable) {
            throw new \InvalidArgumentException('Field "admittedAt" must be a valid ISO-8601 datetime.');
        }

        $patientAdmission = PatientAdmission::admit(
            patientId: $command->patientId,
            hospitalUnitCode: $command->hospitalUnitCode,
            admittedAt: $admittedAt,
            reason: $command->reason,
            createdBy: $command->createdBy,
        );

        $this->patientAdmissionRepository->save($patientAdmission);

        return new PatientAdmissionResponse(
            id: $patientAdmission->id(),
            status: $patientAdmission->status()->value,
            patientId: $patientAdmission->patientId(),
            hospitalUnitCode: $patientAdmission->hospitalUnitCode(),
            admittedAt: $patientAdmission->admittedAt()->format(\DateTimeInterface::ATOM),
            reason: $patientAdmission->reason(),
            createdBy: $patientAdmission->createdBy(),
            createdAt: $patientAdmission->createdAt()->format(\DateTimeInterface::ATOM),
        );
    }
}
