<?php

namespace App\Parser;

use App\Exception\ApiException;
use Symfony\Component\HttpFoundation\Request;

class RequestParser
{
    /**
     * @param Request $request
     *
     * @return int|string
     * @throws ApiException
     */
    public function getId(Request $request)
    {
        $content = $request->getContent();
        $contentArray = json_decode($content, true);

        $id = $contentArray['id'] ?? null;

        if (!$id) {
            throw new ApiException('No id found in the request');
        }

        return $id;
    }
}