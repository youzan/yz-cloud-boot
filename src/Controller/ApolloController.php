<?php

namespace YouzanCloudBoot\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use Symfony\Component\Yaml\Yaml;
use YouzanCloudBoot\Component\BaseComponent;
use YouzanCloudBoot\Constant\Env;
use YouzanCloudBoot\Facades\LogFacade;
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

        $res = $this->writeToFile($configAll);
        return $response->withJson(['status' => $res]);
    }


    private function writeToFile($configAll, $reties = 3): string
    {
        if (empty($configAll)) {
            LogFacade::err("Apollo writeToFile. the config is empty");
            return 'Fail, Apollo writeToFile. the config is empty';
        }

        if ($reties < 0) {
            LogFacade::err("Apollo writeToFile. exceeds the maximum retries");
            return 'Fail, Apollo writeToFile. exceeds the maximum retries';
        }

        // write to file
        $res = file_put_contents(Env::APOLLO_FILE, Yaml::dump($configAll));

        if (false === $res) {
            return $this->writeToFile($configAll, --$reties);
        }

        return 'OK';
    }


}