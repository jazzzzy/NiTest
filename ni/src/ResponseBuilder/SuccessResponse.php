<?php

namespace App\ResponseBuilder;

use Symfony\Component\HttpFoundation\JsonResponse;

class SuccessResponse extends JsonResponse
{
    /**
     * SuccessResponse constructor.
     * @param array $data
     * @param int $statusCode
     */
    public function __construct(array $data, int $statusCode = 200)
    {
        parent::__construct(['data' => $data], $statusCode);
    }
}
