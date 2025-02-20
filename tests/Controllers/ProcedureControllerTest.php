<?php

namespace App\Tests\Controller;

use App\Controller\ProcedureController;
use App\Service\ProcedureService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProcedureControllerTest extends TestCase
{
    public function testGetProceduresReturnsExpectedJson(): void
    {
        $expectedJson = '[{"id":1,"name":"\u0417\u0430\u0431\u043e\u0440 \u043a\u0440\u043e\u0432\u0438 \u0438\u0437 \u043f\u0430\u043b\u044c\u0446\u0430","description":"\u0417\u0430\u0431\u043e\u0440 \u043a\u0440\u043e\u0432\u0438 \u0438\u0437 \u043f\u0430\u043b\u044c\u0446\u0430"},{"id":2,"name":"\u0417\u0430\u0431\u043e\u0440 \u043a\u0440\u043e\u0432\u0438 \u0438\u0437 \u0432\u0435\u043d\u044b","description":"\u0417\u0430\u0431\u043e\u0440 \u043a\u0440\u043e\u0432\u0438 \u0438\u0437 \u0432\u0435\u043d\u044b"}]';

        $procedureServiceStub = $this->createMock(ProcedureService::class);
        $procedureServiceStub->method('getProcedures')
            ->willReturn($expectedJson);

        $controller = new ProcedureController();

        $response = $controller->getProcedures($procedureServiceStub);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $this->assertJsonStringEqualsJsonString($expectedJson, $response->getContent());
    }
}
