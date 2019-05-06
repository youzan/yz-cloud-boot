<?php

namespace YouzanCloudBoot\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use YouzanCloudBoot\Component\BaseComponent;

class HeartbeatController extends BaseComponent
{

    public function handle(Request $request, Response $response, array $args)
    {
        return $response;
    }

}