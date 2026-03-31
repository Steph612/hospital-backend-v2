<?php

declare(strict_types=1);

namespace App\Tests\Unit\PatientAdmission\Application\Create;

use App\PatientAdmission\Domain\Entity\PatientAdmission;
use App\PatientAdmission\Domain\ValueObject\PatientAdmissionStatus;
use PHPUnit\Framework\TestCase;

final class PatientAdmissionTest extends TestCase
{
    public function test_it_admits_a_patient_with_expected_initial_state(): void
    {
        $admittedAt = new \DateTimeImmutable('2026-03-28T10:30:00+01:00');

        $admission = PatientAdmission::admit(
            patientId: '11111111-1111-4111-8111-111111111111',
            hospitalUnitCode: 'cardio',
            admittedAt: $admittedAt,
            reason: 'Chest pain assessment',
            createdBy: '22222222-2222-4222-8222-222222222222',
        );

        self::assertNotSame('', $admission->id());
        self::assertSame('11111111-1111-4111-8111-111111111111', $admission->patientId());
        self::assertSame('CARDIO', $admission->hospitalUnitCode());
        self::assertSame($admittedAt, $admission->admittedAt());
        self::assertSame(PatientAdmissionStatus::ADMITTED, $admission->status());
        self::assertSame('Chest pain assessment', $admission->reason());
        self::assertSame('22222222-2222-4222-8222-222222222222', $admission->createdBy());
    }

    public function test_it_rejects_empty_patient_id(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Patient id cannot be empty.');

        PatientAdmission::admit(
            patientId: '',
            hospitalUnitCode: 'CARDIO',
            admittedAt: new \DateTimeImmutable('2026-03-28T10:30:00+01:00'),
            reason: 'Chest pain assessment',
            createdBy: '22222222-2222-4222-8222-222222222222',
        );
    }

    public function test_it_rejects_empty_hospital_unit_code(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Hospital unit code cannot be empty.');

        PatientAdmission::admit(
            patientId: '11111111-1111-4111-8111-111111111111',
            hospitalUnitCode: '',
            admittedAt: new \DateTimeImmutable('2026-03-28T10:30:00+01:00'),
            reason: 'Chest pain assessment',
            createdBy: '22222222-2222-4222-8222-222222222222',
        );
    }

    public function test_it_rejects_empty_reason(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Reason cannot be empty.');

        PatientAdmission::admit(
            patientId: '11111111-1111-4111-8111-111111111111',
            hospitalUnitCode: 'CARDIO',
            admittedAt: new \DateTimeImmutable('2026-03-28T10:30:00+01:00'),
            reason: '',
            createdBy: '22222222-2222-4222-8222-222222222222',
        );
    }

    public function test_it_rejects_empty_created_by(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Created by cannot be empty.');

        PatientAdmission::admit(
            patientId: '11111111-1111-4111-8111-111111111111',
            hospitalUnitCode: 'CARDIO',
            admittedAt: new \DateTimeImmutable('2026-03-28T10:30:00+01:00'),
            reason: 'Chest pain assessment',
            createdBy: '',
        );
    }
}
