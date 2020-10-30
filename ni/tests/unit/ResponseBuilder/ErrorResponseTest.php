<?php

namespace Tests\Filter;

use App\Exception\ApiException;
use App\Exception\NotFoundException;
use App\ResponseBuilder\ErrorResponse;
use PHPUnit\Framework\TestCase;

/**
 * @covers App\ResponseBuilder\ErrorResponse
 */
final class ErrorResponseTest extends TestCase
{
    /**
     * @dataProvider exceptionDataProvider
     */
    public function testResponseData($exception, $statusCode, $title, $message)
    {
        $response = new ErrorResponse($exception);
        $responseContent = $response->getContent();
        $responseArray = json_decode($responseContent, true);

        $expected = [
            'error' => [
                'code' => $statusCode,
                'title' => $title,
                'message' => $message,
            ]
        ];

        self::assertEquals($expected['error']['code'], $responseArray['error']['code']);
        self::assertEquals($expected['error']['title'], $responseArray['error']['title']);
        self::assertEquals($expected['error']['message'], $responseArray['error']['message']);
    }

    public function exceptionDataProvider(): array
    {
        return [
            [new ApiException('API_EXCEPTION'), 400, 'API_EXCEPTION', 'API_EXCEPTION'],
            [new NotFoundException('NOTFOUND_EXCEPTION'), 404, 'NOTFOUND_EXCEPTION', 'NOTFOUND_EXCEPTION'],
            [new \Exception('API_EXCEPTION'), 400, 'Unknown Error', 'Unknown Error'],
        ];
    }
}
