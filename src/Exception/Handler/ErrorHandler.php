<?php


namespace YouzanCloudBoot\Exception\Handler;

use YouzanCloudBoot\Facades\LogFacade;

class ErrorHandler
{

    /**
     * @param $request \Slim\Http\Request
     * @param $response \Slim\Http\Response
     * @param $exception \Exception
     * @return mixed
     */
    public function __invoke($request, $response, $exception)
    {

        LogFacade::error($exception->__toString());
        $data = [];
        $data['code'] = $exception->getCode() ? $exception->getCode() : -1;
        $data['message'] = $exception->getMessage() ? $exception->getMessage() : 'Unknown Exception';
        $data['success'] = false;

        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->withJson($data);
    }
}