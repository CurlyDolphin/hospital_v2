<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Procedure\CreateProcedureDto;
use App\Service\ProcedureService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/procedures')]
class ProcedureController extends AbstractController
{

    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[OA\Tag(name: 'Procedures')]
    #[OA\Response(
        response: 200,
        description: 'List of procedures',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'id', type: 'integer', example: 1),
                    new OA\Property(property: 'name', type: 'string', example: 'Электрокардиография'),
                    new OA\Property(property: 'description', type: 'string', example: 'Диагностическая процедура, позволяет обнаружить многие болезни сердечно-сосудистой системы'),
                ]
            ),
            example: [
                [
                    'id' => 1,
                    'name' => 'Электрокардиография',
                    'description' => 'Диагностическая процедура, позволяет обнаружить многие болезни сердечно-сосудистой системы',
                ],
                [
                    'id' => 2,
                    'name' => 'Измерение АД',
                    'description' => 'Измерение артериального давления',
                ],
            ]
        )
    )]
    #[Route('/', name: 'get_procedures', methods: ['GET'])]
    public function getProcedures(ProcedureService $procedureService): JsonResponse
    {
        $procedure = $procedureService->getProcedures();

        $response = $this->serializer->serialize($procedure, 'json', ['groups' => 'procedure:read']);

        return new JsonResponse(
            $response,
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[OA\Tag(name: 'Procedures')]
    #[OA\Response(
        response: 201,
        description: 'Procedure created successfully',
        content: new OA\JsonContent(
            example: ['message' => 'Procedure created successfully', 'procedureName' => 'Электрокардиография']
        )
    )]
    #[Route('', name: 'create_procedure', methods: ['POST'])]
    public function createProcedure(
        #[MapRequestPayload] CreateProcedureDto $dto,
        ProcedureService $procedureService,
    ): JsonResponse {
        $procedure = $procedureService->createProcedure($dto);

        return new JsonResponse(
            ['message' => 'Procedure created successfully', 'procedureName' => $procedure->getName()],
            Response::HTTP_CREATED,
        );
    }

    #[OA\Tag(name: 'Procedures')]
    #[OA\Response(
        response: 200,
        description: 'Get procedure info',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'integer', example: 2),
                new OA\Property(property: 'name', type: 'string', example: 'Забор крови из вены'),
                new OA\Property(property: 'description', type: 'string', example: 'Забор крови из вены'),
                new OA\Property(property: 'wards', type: 'array', items: new OA\Items(type: 'string', example: '24')),
                new OA\Property(property: 'patients', type: 'array', items: new OA\Items(type: 'string', example: 'Кирилл Иванов')),
            ],
            type: 'object'
        )
    )]
    #[Route('/{id}', name: 'get_procedure_info', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function getProcedureInfo(
        int $id,
        ProcedureService $procedureService,
    ): JsonResponse {
        $procedureInfo = $procedureService->getProcedureInfo($id);

        $response = $this->serializer->serialize($procedureInfo, 'json');

        return new JsonResponse(
            $procedureInfo,
            Response::HTTP_OK
        );
    }

    #[OA\Tag(name: 'Procedures')]
    #[OA\Response(
        response: 200,
        description: 'Procedure updated successfully',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Procedure updated successfully'),
                new OA\Property(property: 'procedureName', type: 'string', example: 'Забор крови из пальца'),
            ]
        )
    )]
    #[Route('/{id}', name: 'update_procedure', requirements: ['id' => '\d+'], methods: ['PUT'])]
    public function updateProcedure(
        int $procedureId,
        #[MapRequestPayload] CreateProcedureDto $dto,
        ProcedureService $procedureService,
    ): JsonResponse {
        $procedure = $procedureService->updateProcedure($procedureId, $dto);

        return new JsonResponse(
            ['message' => 'Procedure updated successfully', 'procedureName' => $procedure->getName()],
            Response::HTTP_OK
        );
    }

    #[OA\Tag(name: 'Procedures')]
    #[OA\Response(
        response: 200,
        description: 'Procedure deleted successfully',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Procedure deleted successfully'),
            ]
        )
    )]
    #[Route('/{id}', name: 'delete_procedure', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function deleteProcedure(
        int $procedureId,
        ProcedureService $procedureService,
    ): JsonResponse {
        $procedureService->deleteProcedure($procedureId);

        return new JsonResponse(
            ['message' => 'Procedure deleted successfully'],
            Response::HTTP_OK
        );
    }
}
