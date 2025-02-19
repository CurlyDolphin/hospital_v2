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
    #[OA\Response(
        response: 200,
        description: 'Get ward procedures',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(
                        property: 'procedure',
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 1),
                            new OA\Property(property: 'name', type: 'string', example: 'Измерение АД'),
                            new OA\Property(property: 'description', type: 'string', example: 'Измерение артериального давления'),
                        ],
                        type: 'object'
                    ),
                    new OA\Property(property: 'sequence', type: 'integer', example: 1),
                ],
                type: 'object'
            ),
            example: [
                [
                    'procedure' => [
                        'id' => 1,
                        'name' => 'Измерение АД',
                        'description' => 'Измерение артериального давления'
                    ],
                    'sequence' => 1
                ],
                [
                    'procedure' => [
                        'id' => 3,
                        'name' => 'Забор крови из пальца',
                        'description' => 'Забор крови из пальца'
                    ],
                    'sequence' => 2
                ],
                [
                    'procedure' => [
                        'id' => 2,
                        'name' => 'Забор крови из вены',
                        'description' => 'Забор крови из вены'
                    ],
                    'sequence' => 3
                ]
            ]
        )
    )]
    #[Route('/wards/{wardId}/procedures', name: 'get_healing_plan', methods: ['GET'])]
    public function getWardProcedures(
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
