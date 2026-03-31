<?php

declare(strict_types=1);

namespace App\PatientAdmission\UI\Http;

use App\PatientAdmission\Application\Create\CreatePatientAdmissionCommand;
use App\PatientAdmission\Application\Create\CreatePatientAdmissionHandler;
use App\PatientAdmission\Domain\Exception\PatientAlreadyAdmitted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/patient-admissions', name: 'api_patient_admissions_create', methods: ['POST'])]
final readonly class CreatePatientAdmissionController
{
    public function __construct(
        private ValidatorInterface $validator,
        private CreatePatientAdmissionHandler $handler,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $payload = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return new JsonResponse(
                ['message' => 'Invalid JSON payload.'],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $command = new CreatePatientAdmissionCommand(
            patientId: (string) ($payload['patientId'] ?? ''),
            hospitalUnitCode: (string) ($payload['hospitalUnitCode'] ?? ''),
            admittedAt: (string) ($payload['admittedAt'] ?? ''),
            reason: (string) ($payload['reason'] ?? ''),
            createdBy: (string) ($payload['createdBy'] ?? ''),
        );

        $violations = $this->validator->validate($command);

        if (\count($violations) > 0) {
            $errors = [];

            foreach ($violations as $violation) {
                $errors[] = [
                    'field' => $violation->getPropertyPath(),
                    'message' => $violation->getMessage(),
                ];
            }

            return new JsonResponse(
                [
                    'message' => 'Validation failed.',
                    'errors' => $errors,
                ],
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $response = $this->handler->handle($command);

        return new JsonResponse(
            [
                'id' => $response->id,
                'status' => $response->status,
                'patientId' => $response->patientId,
                'hospitalUnitCode' => $response->hospitalUnitCode,
                'admittedAt' => $response->admittedAt,
                'reason' => $response->reason,
                'createdBy' => $response->createdBy,
                'createdAt' => $response->createdAt,
            ],
            JsonResponse::HTTP_CREATED
        );
    }
}
