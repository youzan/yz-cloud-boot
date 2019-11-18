<?php

namespace YouzanCloudBoot\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use Symfony\Component\Yaml\Yaml;
use YouzanCloudBoot\Component\BaseComponent;
use YouzanCloudBoot\Constant\Env;
use YouzanCloudBoot\Util\ApolloUtil;

class ApolloController extends BaseComponent
{

    public function handle(Request $request, Response $response, array $args)
    {
        /** @var ApolloUtil $apollo */
        $apollo = $this->getContainer()->get('apolloUtil');

        $configSystem = $apollo->get('system');
        $configApplication = $apollo->get('application');
        $configAll = array_merge($configApplication, $configSystem);

        file_put_contents(Env::APOLLO_FILE, Yaml::dump($configAll));
        return $response->withJson(['status' => 'OK']);
    }


}