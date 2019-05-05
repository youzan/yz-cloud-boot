<?php

namespace YouzanCloudBoot\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use YouzanCloudBoot\Component\BaseComponent;

class HealthController extends BaseComponent
{

    public function handle(Request $request, Response $response, array $args)
    {
        return $response->withJson(['status' => 'UP']);
    }

}