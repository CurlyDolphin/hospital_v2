<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Patient\CreatePatientDto;
use App\Dto\Patient\IdentifyPatientDto;
use App\Dto\Patient\UpdatePatientDto;
use App\Service\PatientService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/patients')]
class PatientController extends AbstractController
{
    #[OA\Tag(name: 'Patients')]
    #[OA\Response(
        response: 200,
        description: 'List of patients',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'id', type: 'integer', example: 2),
                    new OA\Property(property: 'name', type: 'string', example: 'Кирилл'),
                    new OA\Property(property: 'lastName', type: 'string', example: 'Иванов'),
                    new OA\Property(property: 'gender', type: 'string', example: 'male'),
                    new OA\Property(property: 'isIdentified', type: 'boolean', example: true),
                    new OA\Property(property: 'birthday', type: 'string', format: 'date-time', example: '2005-01-01T00:00:00+00:00'),
                    new OA\Property(property: 'cardNumber', type: 'integer', example: 1),
                    new OA\Property(
                        property: 'hospitalizations',
                        type: 'array',
                        items: new OA\Items(type: 'object'),
                        example: []
                    ),
                ]
            )
        )
    )]
    #[Route('/', name: 'get_patients', methods: ['GET'])]
    public function getAllPatients(
        PatientService $patientService,
    ): JsonResponse {
        return new JsonResponse($patientService->getPatients(), Response::HTTP_OK, [], true);
    }

    #[OA\Tag(name: 'Patients')]
    #[OA\Response(
        response: 200,
        description: 'get patient by id',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: 2),
                new OA\Property(property: 'name', type: 'string', example: 'Кирилл'),
                new OA\Property(property: 'lastName', type: 'string', example: 'Иванов'),
                new OA\Property(property: 'gender', type: 'string', example: 'male'),
                new OA\Property(property: 'isIdentified', type: 'boolean', example: true),
                new OA\Property(property: 'birthday', type: 'string', format: 'date-time', example: '2000-01-01T00:00:00+00:00'),
                new OA\Property(property: 'cardNumber', type: 'integer', example: 1),
                new OA\Property(
                    property: 'hospitalizations',
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(
                                property: 'ward',
                                properties: [
                                    new OA\Property(property: 'wardNumber', type: 'integer', example: 24),
                                ],
                                type: 'object'
                            ),
                            new OA\Property(property: 'dischargeDate', type: 'string', format: 'date-time', example: null),
                        ],
                        type: 'object'
                    )
                ),
            ],
            type: 'object'
        )
    )]
    #[Route('/{patientId}', name: 'get_patient', methods: ['GET'])]
    public function getPatientInfo(
        int $patientId,
        PatientService $patientService,
    ): JsonResponse {
        $patientInfo = $patientService->getPatientInfo($patientId);

        return new JsonResponse($patientInfo, Response::HTTP_OK, [], true);
    }

    #[OA\Tag(name: 'Patients')]
    #[OA\Response(
        response: 201,
        description: 'create patient',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Patient created successfully'),
                new OA\Property(
                    property: 'patientName',
                    type: 'string',
                    example: 'Кирилл'
                ),
            ]
        )
    )]
    #[Route('/', name: 'create_patient', methods: ['POST'])]
    public function createPatient(
        #[MapRequestPayload] CreatePatientDto $dto,
        PatientService $patientService,
    ): JsonResponse {
        $patient = $patientService->createPatient($dto);

        return new JsonResponse(
            ['message' => 'Patient created successfully', 'patientName' => $patient->getName()],
            Response::HTTP_CREATED
        );
    }

    #[OA\Tag(name: 'Patients')]
    #[OA\Response(
        response: 200,
        description: 'Identify patient by id',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Patient identified successfully'),
                new OA\Property(property: 'patientName', type: 'string', example: 'Андрей'),
            ]
        )
    )]
    #[Route('/identify/{$patientId}', name: 'identify_patient', methods: ['PUT'])]
    public function identifyPatient(
        #[MapRequestPayload] IdentifyPatientDto $dto,
        int $patientId,
        PatientService $patientService,
    ): JsonResponse {
        $patient = $patientService->identifyPatient($patientId, $dto);

        return new JsonResponse(
            ['message' => 'Patient identified successfully', 'patientName' => $patient->getName()],
            Response::HTTP_OK
        );
    }

    #[OA\Tag(name: 'Patients')]
    #[OA\Response(
        response: 200,
        description: 'delete patient',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Patient deleted successfully'),
            ]
        )
    )]
    #[Route('/{id}', name: 'delete_patient', methods: ['DELETE'])]
    public function deletePatient(
        int $patientId,
        PatientService $patientService,
    ): JsonResponse {
        $patientService->deletePatient($patientId);

        return new JsonResponse(['message' => 'Patient deleted successfully'], Response::HTTP_OK);
    }

    #[OA\Tag(name: 'Patients')]
    #[OA\Response(
        response: 200,
        description: 'update patient',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: 8),
                new OA\Property(
                    property: 'name',
                    type: 'string',
                    example: 'Лариса'
                ),
            ]
        )
    )]
    #[Route('/{id}', name: 'update_patient', methods: ['PUT'])]
    public function updatePatient(
        int $patientId,
        #[MapRequestPayload] UpdatePatientDto $dto,
        PatientService $patientService,
    ): JsonResponse {
        $patient = $patientService->updatePatient($patientId, $dto);

        return new JsonResponse(
            ['message' => 'Patient updated successfully', 'Patient Name' => $patient->getName()],
            Response::HTTP_OK
        );
    }
}
