<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Ward\CreateWardDto;
use App\Service\WardService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

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
                    new OA\Property(property: 'wardNumber', type: 'integer', example: 63),
                    new OA\Property(property: 'description', type: 'string', example: 'Инфекционная палата'),
                ]
            )
        )
    )]
    public function getWards(WardService $wardService): JsonResponse
    {
        return new JsonResponse($wardService->getWards(), Response::HTTP_OK, [], true);
    }

    #[Route('/wards', name: 'create_wards', methods: ['POST'])]
    public function createWards(
        #[MapRequestPayload] CreateWardDto $dto,
        WardService                        $wardService,
    ): JsonResponse
    {
        $ward = $wardService->createWard($dto);

        return new JsonResponse(
            ['message' => 'Ward created successfully', 'Ward Number' => $ward->getWardNumber()],
            Response::HTTP_CREATED
        );
    }

    #[Route('/wards/{id}', name: 'update_ward', methods: ['PUT'])]
    public function updateWard(
        int $id,
        #[MapRequestPayload] CreateWardDto $dto,
        WardService $wardService,
    ): JsonResponse
    {
        $ward = $wardService->updateWard($id, $dto);

        return new JsonResponse(
            ['message' => 'Ward updated successfully', 'Ward Number' => $ward->getWardNumber()],
            Response::HTTP_OK
        );
    }

    #[Route('/wards/{id}', name: 'get_ward_info', methods: ['GET'])]
    public function getWardInfo(
        int         $id,
        WardService $wardService
    ): JsonResponse
    {
        $wardInfo = $wardService->getWardInfo($id);

        return new JsonResponse($wardInfo, Response::HTTP_OK);
    }

    #[Route('/wards/{wardId}', name: 'delete_ward', methods: ['DELETE'])]
    public function deleteWard(
        int         $wardId,
        WardService $wardService
    ): JsonResponse
    {
        $wardService->deleteWard($wardId);

        return new JsonResponse(['message' => 'Палата успешно удалена. Пациенты отцеплены.'], Response::HTTP_OK);
    }
}