<?php

declare(strict_types=1);

namespace App\PatientAdmission\Domain\Entity;

use App\PatientAdmission\Domain\ValueObject\PatientAdmissionStatus;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'patient_admissions')]
#[ORM\UniqueConstraint(name: 'uniq_patient_admission_id', columns: ['id'])]
#[ORM\Index(name: 'idx_patient_admissions_patient_id', columns: ['patient_id'])]
#[ORM\Index(name: 'idx_patient_admissions_status', columns: ['status'])]
final class PatientAdmission
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    private string $id;

    #[ORM\Column(name: 'patient_id', type: 'string', length: 36)]
    private string $patientId;

    #[ORM\Column(name: 'hospital_unit_code', type: 'string', length: 20)]
    private string $hospitalUnitCode;

    #[ORM\Column(name: 'admitted_at', type: 'datetime_immutable')]
    private \DateTimeImmutable $admittedAt;

    #[ORM\Column(type: 'string', enumType: PatientAdmissionStatus::class)]
    private PatientAdmissionStatus $status;

    #[ORM\Column(type: 'string', length: 255)]
    private string $reason;

    #[ORM\Column(name: 'created_by', type: 'string', length: 36)]
    private string $createdBy;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    private function __construct(
        string $id,
        string $patientId,
        string $hospitalUnitCode,
        \DateTimeImmutable $admittedAt,
        string $reason,
        string $createdBy,
        \DateTimeImmutable $createdAt,
    ) {
        if (trim($patientId) === '') {
            throw new \InvalidArgumentException('Patient id cannot be empty.');
        }

        if (trim($hospitalUnitCode) === '') {
            throw new \InvalidArgumentException('Hospital unit code cannot be empty.');
        }

        if (trim($reason) === '') {
            throw new \InvalidArgumentException('Reason cannot be empty.');
        }

        if (trim($createdBy) === '') {
            throw new \InvalidArgumentException('Created by cannot be empty.');
        }

        $this->id = $id;
        $this->patientId = $patientId;
        $this->hospitalUnitCode = mb_strtoupper(trim($hospitalUnitCode));
        $this->admittedAt = $admittedAt;
        $this->status = PatientAdmissionStatus::ADMITTED;
        $this->reason = trim($reason);
        $this->createdBy = $createdBy;
        $this->createdAt = $createdAt;
    }

    public static function admit(
        string $patientId,
        string $hospitalUnitCode,
        \DateTimeImmutable $admittedAt,
        string $reason,
        string $createdBy,
    ): self {
        return new self(
            Uuid::v7()->toRfc4122(),
            $patientId,
            $hospitalUnitCode,
            $admittedAt,
            $reason,
            $createdBy,
            new \DateTimeImmutable(),
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function patientId(): string
    {
        return $this->patientId;
    }

    public function hospitalUnitCode(): string
    {
        return $this->hospitalUnitCode;
    }

    public function admittedAt(): \DateTimeImmutable
    {
        return $this->admittedAt;
    }

    public function status(): PatientAdmissionStatus
    {
        return $this->status;
    }

    public function reason(): string
    {
        return $this->reason;
    }

    public function createdBy(): string
    {
        return $this->createdBy;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
