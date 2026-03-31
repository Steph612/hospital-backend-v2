<?php

declare(strict_types=1);

namespace App\PatientAdmission\UI\Http;

use App\PatientAdmission\Application\Create\CreatePatientAdmissionCommand;
use App\PatientAdmission\Application\Create\CreatePatientAdmissionHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/patient-admissions', name: 'api_patient_admissions_create', methods: ['POST'])]
final readonly class CreatePatientAdmissionController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private CreatePatientAdmissionHandler $handler,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            /** @var CreatePatientAdmissionRequest $input */
            $input = $this->serializer->deserialize(
                $request->getContent(),
                CreatePatientAdmissionRequest::class,
                'json'
            );
        } catch (SerializerExceptionInterface|\JsonException) {
            return new JsonResponse(
                ['message' => 'Invalid JSON payload.'],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $violations = $this->validator->validate($input);

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

        $response = $this->handler->handle(new CreatePatientAdmissionCommand(
            patientId: $input->patientId,
            hospitalUnitCode: $input->hospitalUnitCode,
            admittedAt: $input->admittedAt,
            reason: $input->reason,
            createdBy: $input->createdBy,
        ));

        $json = $this->serializer->serialize($response, 'json');

        return new JsonResponse($json, JsonResponse::HTTP_CREATED, [], true);
    }
}
