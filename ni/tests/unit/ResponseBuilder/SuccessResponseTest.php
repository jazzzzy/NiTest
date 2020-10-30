<?php

namespace Tests\Filter;

use App\ResponseBuilder\SuccessResponse;
use PHPUnit\Framework\TestCase;

/**
 * @covers App\ResponseBuilder\ErrorResponse
 */
final class SuccessResponseTest extends TestCase
{
    /**
     * @dataProvider exceptionDataProvider
     */
    public function testResponseData($data, $statusCode, $expectedStatus)
    {
        $response = new SuccessResponse($data, $statusCode);
        $responseContent = $response->getContent();
        $statusCode = $response->getStatusCode();
        $responseArray = json_decode($responseContent, true);

        $expected = ['data' => $data];

        self::assertEquals($expected['data']['example'], $responseArray['data']['example']);
        self::assertEquals($expectedStatus, $statusCode);
    }

    public function exceptionDataProvider(): array
    {
        return [
            [['example'=>'data'], 201, 201],
            [['example'=>'other_data'], 200, 200],
        ];
    }
}
