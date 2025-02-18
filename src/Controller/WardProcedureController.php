<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\WardProcedure\UpdateWardProcedureDto;
use App\Entity\WardProcedure;
use App\Service\WardProcedureService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

class WardProcedureController extends AbstractController
{
    #[Route('/wards/{wardId}/procedures', name: 'get_healing_plan', methods: ['GET'])]
    public function getWardProcedure(
        int                  $wardId,
        WardProcedureService $wardProcedureService
    ): JsonResponse
    {
        $jsonData = $wardProcedureService->getWardProcedures($wardId);

        return new JsonResponse($jsonData, Response::HTTP_OK, [], true);
    }

    #[OA\Response(
        response: 200,
        description: 'Ward procedures updated successfully',
        content: new OA\JsonContent(
            example: ['message' => 'Ward procedures updated successfully']
        )
    )]
    #[Route('/wards/{wardId}/procedures', name: 'create_healing_plan', methods: ['POST'])]
    public function updateWardProcedure(
        int $wardId,
        #[MapRequestPayload] UpdateWardProcedureDto $dto,
        WardProcedureService                        $wardProcedureService
    ): JsonResponse
    {
        $wardProcedures = $wardProcedureService->updateWardProcedures($wardId, $dto);

        return new JsonResponse(
            ['message' => 'Ward procedures updated successfully'],
            Response::HTTP_OK
        );
    }
}
