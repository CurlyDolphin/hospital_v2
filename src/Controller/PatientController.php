<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Patient\CreatePatientDto;
use App\Dto\Patient\IdentifyPatientDto;
use App\Service\PatientService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

class PatientController extends AbstractController
{
    #[Route('/patients', name: 'get_patients', methods: ['GET'])]
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
    public function getAllPatients(
        PatientService $patientService
    ): JsonResponse
    {
        return new JsonResponse($patientService->getPatients(), Response::HTTP_OK, [], true);
    }

    #[Route('/patients/{patientId}', name: 'get_patient', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'get patient by id',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'id', type: 'integer', example: 2),
                    new OA\Property(
                        property: 'name',
                        type: 'string',
                        example: 'Кирилл'
                    ),
                ]
            )
        )
    )]
    public function getPatientInfo(
        int            $patientId,
        PatientService $patientService
    ): JsonResponse
    {
        $patientInfo = $patientService->getPatientInfo($patientId);

        return new JsonResponse($patientInfo, Response::HTTP_OK, [], true);
    }

    #[Route('/patients', name: 'create_patient', methods: ['POST'])]
    #[OA\Response(
        response: 201,
        description: 'create patient',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'id', type: 'integer', example: 2),
                    new OA\Property(
                        property: 'name',
                        type: 'string',
                        example: 'Кирилл'
                    ),
                ]
            )
        )
    )]
    public function createPatient(
        #[MapRequestPayload] CreatePatientDto $dto,
        PatientService $patientService,
    ): JsonResponse
    {
        $patient = $patientService->createPatient($dto);

        return new JsonResponse(
            ['message' => 'Patient created successfully', 'patientName' => $patient->getName()],
            Response::HTTP_CREATED
        );
    }

    #[Route('/patients/identify/{id}', name: 'identify_patient', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Identify patient by id',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'message', type: 'string', example: 'Patient identified successfully'),
                    new OA\Property(property: 'patientName', type: 'string', example: 'Андрей'),
                ]
            )
        )
    )]
    public function identifyPatient(
        #[MapRequestPayload] IdentifyPatientDto $dto,
        int                                     $id,
        PatientService $patientService
    ): JsonResponse
    {
        $patient = $patientService->identifyPatient($id, $dto);

        return new JsonResponse(
            ['message' => 'Patient identified successfully', 'patientName' => $patient->getName()],
            Response::HTTP_OK
        );
    }

    #[Route('/patients/{id}', name: 'delete_patient', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'delete patient',
        content: new OA\JsonContent()
    )]
    public function deletePatient(
        int            $id,
        PatientService $patientService
    ): JsonResponse
    {
        $patientService->deletePatient($id);

        return new JsonResponse(['message' => 'Patient deleted successfully'], Response::HTTP_OK);
    }
}
