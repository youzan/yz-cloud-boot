<?php

namespace YouzanCloudBoot\Controller\Health;

use Slim\Http\Request;
use Slim\Http\Response;
use YouzanCloudBoot\Component\BaseComponent;

class HealthController extends BaseComponent
{

    public function handle(Request $request, Response $response, array $args)
    {
        $health = new Health();
        $health->setStatus("UP");
        return $response->withJson($health);
    }

}