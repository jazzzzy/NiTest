<?php

namespace App\ResponseBuilder;

use App\Exception\ApiException;
use App\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;

class ErrorResponse extends JsonResponse
{
    const UNKNOWN_ERROR_MESSAGE = 'Unknown Error';

    const STATUS_CODE_ERROR = 400;
    const STATUS_CODE_AUTH_FAILED = 401;
    const STATUS_CODE_NOT_FOUND = 404;

    /**
     * @param \Throwable $exception
     * @param null $message
     * @param int $statusCode
     */
    public function __construct(\Throwable $exception, $message = null, $statusCode = self::STATUS_CODE_ERROR)
    {
        $title = static::UNKNOWN_ERROR_MESSAGE;
        $message = static::UNKNOWN_ERROR_MESSAGE;

        if ($exception instanceof ApiException) {
            $title = $exception->getMessage();
            $message = $title;
        }

        if ($exception instanceof NotFoundException) {
            $statusCode = static::STATUS_CODE_NOT_FOUND;
        }

        $response = [
            'error' => [
                'code' => $statusCode,
                'title' => $title,
                'message' => $message,
            ]
        ];

        parent::__construct($response, $statusCode);
    }
}
