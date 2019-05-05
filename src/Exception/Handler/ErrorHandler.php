<?php


namespace YouzanCloudBoot\Exception\Handler;

use Exception;

class ErrorHandler
{
    public function __invoke($request, $response, Exception $exception)
    {

        $data = [];
        $data['code'] = $exception->getCode() ? $exception->getCode() : -1;
        $data['message'] = $exception->getMessage() ? $exception->getMessage() : 'Unknown Exception';
        $data['success'] = false;

        return $response
            ->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->withJson($data);
    }
}