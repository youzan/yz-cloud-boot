<?php


namespace YouzanCloudBoot\Exception\Handler;

class ErrorHandler
{
    public function __invoke($request, $response, $exception)
    {

        $data = [];
        $data['code'] = $exception->getCode() ? $exception->getCode() : -1;
        $data['message'] = $exception->getMessage() ? $exception->getMessage() : 'Unknown Exception';
        $data['success'] = false;

        return $response
            ->withStatus(500)
            ->withHeader('Content-Type', 'application/json')
            ->withJson($data);
    }
}