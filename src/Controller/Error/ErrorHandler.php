<?php


namespace YouzanCloudBoot\Controller\Error;

class ErrorHandler
{
    public function __invoke($request, $response, $exception) {

        $code = $exception->getCode();
        if (!isset($code)) {
            $code = -1;
        }

        $message = $exception->getMessage();
        if (empty($message)) {
            $message = 'Unknown Exception';
        }

        $errorResponse = new ErrorResponse();
        $errorResponse->setSuccess(false);
        $errorResponse->setCode($code);
        $errorResponse->setMessage($message);

        return $response
            ->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->withJson($errorResponse);
    }
}