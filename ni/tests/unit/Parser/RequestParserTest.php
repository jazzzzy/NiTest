<?php

namespace Tests\Filter;

use App\Exception\ApiException;
use App\Parser\RequestParser;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @covers App\Parser\RequestParser
 */
final class RequestParserTest extends TestCase
{

    public function testGetIdSuccess()
    {
        $request = new Request([], [], [], [], [], [], '{"id":"1"}');
        $parser = new RequestParser();

        $id = $parser->getId($request);

        self::assertEquals(1, $id);
    }

    /**
     * @dataProvider exceptionDataProvider
     */
    public function testGetIdThrowsExceptionIfNoIdEmpty($content, $expectedException, $expectedExceptionMessage)
    {
        $request = new Request([], [], [], [], [], [], $content);
        $parser = new RequestParser();

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);

        $parser->getId($request);
    }

    public function exceptionDataProvider(): array
    {
        return [
            ['{"id":null}', ApiException::class, 'No id found in the request'],
            ['{"id":false}', ApiException::class, 'No id found in the request'],
            ['{"NoId":"1"}', ApiException::class, 'No id found in the request']
        ];
    }
}
