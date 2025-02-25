<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Ward\CreateWardDto;
use App\Service\WardService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class WardController extends AbstractController
{
    #[Route('/wards', name: 'get_wards', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'List of wards',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'wardNumber', type: 'integer', example: 24),
                    new OA\Property(property: 'description', type: 'string', example: 'Палата сердечно сосудистых заболеваний'),
                ],
                type: 'object'
            ),
            example: [
                [
                    'wardNumber' => 24,
                    'description' => 'Палата сердечно сосудистых заболеваний',
                ],
                [
                    'wardNumber' => 32,
                    'description' => 'Инфекционная палата',
                ],
            ]
        )
    )]
    public function getWards(WardService $wardService): JsonResponse
    {
        return new JsonResponse($wardService->getWards(), Response::HTTP_OK, [], true);
    }

    #[Route('/wards', name: 'create_wards', methods: ['POST'])]
    #[OA\Response(
        response: 201,
        description: 'Create ward',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Ward created successfully'),
                new OA\Property(property: 'wardNumber', type: 'integer', example: 32),
            ]
        )
    )]
    public function createWards(
        #[MapRequestPayload] CreateWardDto $dto,
        WardService $wardService,
    ): JsonResponse {
        $ward = $wardService->createWard($dto);

        return new JsonResponse(
            ['message' => 'Ward created successfully', 'Ward Number' => $ward->getWardNumber()],
            Response::HTTP_CREATED
        );
    }

    #[Route('/wards/{id}', name: 'update_ward', requirements: ['id' => '\d+'], methods: ['PUT'])]
    #[OA\Response(
        response: 200,
        description: 'Update ward',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Ward updated successfully'),
                new OA\Property(property: 'Ward Number', type: 'integer', example: 32),
            ],
            type: 'object'
        )
    )]
    public function updateWard(
        int $wardId,
        #[MapRequestPayload] CreateWardDto $dto,
        WardService $wardService,
    ): JsonResponse {
        $ward = $wardService->updateWard($wardId, $dto);

        return new JsonResponse(
            ['message' => 'Ward updated successfully', 'Ward Number' => $ward->getWardNumber()],
            Response::HTTP_OK
        );
    }

    #[Route('/wards/{id}', name: 'get_ward_info', requirements: ['id' => '\d+'], methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Get ward with patient by id',
        content: new OA\JsonContent(
            properties: [
                new OA\Property('wardNumber', type: 'integer', example: 24),
                new OA\Property(
                    property: 'patients',
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 2),
                            new OA\Property(property: 'name', type: 'string', example: 'Кирилл'),
                            new OA\Property(property: 'lastName', type: 'string', example: 'Иванов'),
                        ],
                        type: 'object'
                    )
                ),
            ],
            type: 'object'
        )
    )]
    public function getWardInfo(
        int $wardId,
        WardService $wardService,
    ): JsonResponse {
        $wardInfo = $wardService->getWardInfo($wardId);

        return new JsonResponse($wardInfo, Response::HTTP_OK);
    }

    #[Route('/wards/{wardId}', name: 'delete_ward', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'Delete ward',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'The ward has been successfully removed. Patients are disconnected.'),
            ]
        )
    )]
    public function deleteWard(
        int $wardId,
        WardService $wardService,
    ): JsonResponse {
        $wardService->deleteWard($wardId);

        return new JsonResponse(['message' => 'The ward has been successfully removed. Patients are disconnected.'], Response::HTTP_OK);
    }
}
