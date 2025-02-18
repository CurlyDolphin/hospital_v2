<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Hospitalization\AssignPatientDto;
use App\Dto\Hospitalization\UpdatePatientDto;
use App\Service\HospitalizationService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class HospitalizationController extends AbstractController
{
    #[Route('/patients/assign', name: 'assign_patient_to_ward', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'assign patient to ward',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'message', type: 'string', example: 'Patient assigned to ward successfully'),
                    new OA\Property(property: 'patientName', type: 'string', example: 'Кирилл Иванов'),
                ]
            )
        )
    )]
    public function assignPatientToWard(
        #[MapRequestPayload] AssignPatientDto $dto,
        HospitalizationService                $hospitalizationService
    ): JsonResponse
    {
        $patient = $hospitalizationService->assignPatientToWard($dto);

        return new JsonResponse(['message' => 'Patient assigned to ward successfully', 'patientName' => $patient->getName()], Response::HTTP_OK);
    }

    #[Route('/patients/{id}', name: 'update_patient', methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'update patient',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'id', type: 'integer', example: 8),
                    new OA\Property(
                        property: 'name',
                        type: 'string',
                        example: 'Лариса'
                    ),
                ]
            )
        )
    )]
    public function updatePatient(
        int $id,
        #[MapRequestPayload] UpdatePatientDto $dto,
        HospitalizationService                $hospitalizationService
    ): JsonResponse
    {
        $patient = $hospitalizationService->updatePatient($id, $dto);

        return new JsonResponse(
            ['message' => 'Patient updated successfully', 'Patient Name' => $patient->getName()],
            Response::HTTP_OK
        );
    }
}
