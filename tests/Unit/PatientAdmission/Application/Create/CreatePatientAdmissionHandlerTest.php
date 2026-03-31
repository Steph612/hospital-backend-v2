<?php

declare(strict_types=1);

namespace App\Tests\Unit\PatientAdmission\Application\Create;

use App\PatientAdmission\Application\Create\CreatePatientAdmissionCommand;
use App\PatientAdmission\Application\Create\CreatePatientAdmissionHandler;
use App\PatientAdmission\Domain\Entity\PatientAdmission;
use App\PatientAdmission\Domain\Exception\PatientAlreadyAdmitted;
use App\PatientAdmission\Domain\Repository\PatientAdmissionRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class CreatePatientAdmissionHandlerTest extends TestCase
{
    public function test_it_creates_a_patient_admission_when_patient_has_no_active_admission(): void
    {
        $repository = new InMemoryPatientAdmissionRepository(existsActiveAdmission: false);
        $handler = new CreatePatientAdmissionHandler($repository);

        $response = $handler->handle(new CreatePatientAdmissionCommand(
            patientId: '11111111-1111-4111-8111-111111111111',
            hospitalUnitCode: 'CARDIO',
            admittedAt: '2026-03-28T10:30:00+01:00',
            reason: 'Chest pain assessment',
            createdBy: '22222222-2222-4222-8222-222222222222',
        ));

        self::assertNotSame('', $response->id);
        self::assertSame('ADMITTED', $response->status);
        self::assertSame('11111111-1111-4111-8111-111111111111', $response->patientId);
        self::assertSame('CARDIO', $response->hospitalUnitCode);
        self::assertSame('Chest pain assessment', $response->reason);
        self::assertSame('22222222-2222-4222-8222-222222222222', $response->createdBy);
        self::assertCount(1, $repository->savedAdmissions);
    }

    public function test_it_rejects_patient_when_an_active_admission_already_exists(): void
    {
        $repository = new InMemoryPatientAdmissionRepository(existsActiveAdmission: true);
        $handler = new CreatePatientAdmissionHandler($repository);

        $this->expectException(PatientAlreadyAdmitted::class);
        $this->expectExceptionMessage('Patient "11111111-1111-4111-8111-111111111111" already has an active admission.');

        $handler->handle(new CreatePatientAdmissionCommand(
            patientId: '11111111-1111-4111-8111-111111111111',
            hospitalUnitCode: 'CARDIO',
            admittedAt: '2026-03-28T10:30:00+01:00',
            reason: 'Chest pain assessment',
            createdBy: '22222222-2222-4222-8222-222222222222',
        ));
    }

    public function test_it_rejects_invalid_admitted_at_format(): void
    {
        $repository = new InMemoryPatientAdmissionRepository(existsActiveAdmission: false);
        $handler = new CreatePatientAdmissionHandler($repository);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Field "admittedAt" must be a valid ISO-8601 datetime.');

        $handler->handle(new CreatePatientAdmissionCommand(
            patientId: '11111111-1111-4111-8111-111111111111',
            hospitalUnitCode: 'CARDIO',
            admittedAt: 'not-a-date',
            reason: 'Chest pain assessment',
            createdBy: '22222222-2222-4222-8222-222222222222',
        ));
    }
}

/**
 * @internal
 */
final class InMemoryPatientAdmissionRepository implements PatientAdmissionRepositoryInterface
{
    /** @var list<PatientAdmission> */
    public array $savedAdmissions = [];

    public function __construct(
        private readonly bool $existsActiveAdmission,
    ) {
    }

    public function save(PatientAdmission $patientAdmission): void
    {
        $this->savedAdmissions[] = $patientAdmission;
    }

    public function existsActiveAdmissionForPatient(string $patientId): bool
    {
        return $this->existsActiveAdmission;
    }
}
