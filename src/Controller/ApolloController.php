<?php

namespace YouzanCloudBoot\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use YouzanCloudBoot\Component\BaseComponent;
use YouzanCloudBoot\Util\ApolloUtil;

class ApolloController extends BaseComponent
{

    public function handle(Request $request, Response $response, array $args)
    {
        /** @var ApolloUtil $apollo */
        $apollo = $this->getContainer()->get('apolloUtil');
        $apollo->writeToFile();

        return $response->withJson(['status' => 'OK']);
    }


}