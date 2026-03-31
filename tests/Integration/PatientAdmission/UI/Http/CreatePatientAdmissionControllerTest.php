<?php

declare(strict_types=1);

namespace App\Tests\Integration\PatientAdmission\UI\Http;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CreatePatientAdmissionControllerTest extends WebTestCase
{
    private const ENDPOINT = '/api/patient-admissions';

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
    }

    public function test_it_returns_201_when_payload_is_valid(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            self::ENDPOINT,
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'patientId' => 'aaaaaaaa-aaaa-5aaa-8aaa-aaaaaaaaaaaa',
                'hospitalUnitCode' => 'CARDIO',
                'admittedAt' => '2026-03-28T10:30:00+01:00',
                'reason' => 'Chest pain assessment',
                'createdBy' => 'bbbbbbbb-bbbb-5bbb-8bbb-bbbbbbbbbbbb',
            ], JSON_THROW_ON_ERROR),
        );

        self::assertResponseStatusCodeSame(201);

        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertArrayHasKey('id', $data);
        self::assertSame('ADMITTED', $data['status']);
        self::assertSame('aaaaaaaa-aaaa-5aaa-8aaa-aaaaaaaaaaaa', $data['patientId']);
        self::assertSame('CARDIO', $data['hospitalUnitCode']);
        self::assertSame('Chest pain assessment', $data['reason']);
        self::assertSame('bbbbbbbb-bbbb-5bbb-8bbb-bbbbbbbbbbbb', $data['createdBy']);
    }

    public function test_it_returns_422_when_payload_is_invalid(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            self::ENDPOINT,
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode([
                'patientId' => '',
                'hospitalUnitCode' => '',
                'admittedAt' => '',
                'reason' => '',
                'createdBy' => '',
            ], JSON_THROW_ON_ERROR),
        );

        self::assertResponseStatusCodeSame(422);

        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertSame('Validation failed.', $data['message']);
        self::assertArrayHasKey('errors', $data);
        self::assertNotEmpty($data['errors']);
    }

    public function test_it_returns_409_when_patient_already_has_an_active_admission(): void
    {
        $client = static::createClient();

        $payload = [
            'patientId' => 'cccccccc-cccc-5ccc-8ccc-cccccccccccc',
            'hospitalUnitCode' => 'CARDIO',
            'admittedAt' => '2026-03-28T10:30:00+01:00',
            'reason' => 'Chest pain assessment',
            'createdBy' => 'dddddddd-dddd-5ddd-8ddd-dddddddddddd',
        ];

        $client->request(
            'POST',
            self::ENDPOINT,
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($payload, JSON_THROW_ON_ERROR),
        );

        self::assertResponseStatusCodeSame(201);

        $client->request(
            'POST',
            self::ENDPOINT,
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($payload, JSON_THROW_ON_ERROR),
        );

        self::assertResponseStatusCodeSame(409);

        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertSame(
            'Patient "cccccccc-cccc-5ccc-8ccc-cccccccccccc" already has an active admission.',
            $data['message']
        );
    }
}
