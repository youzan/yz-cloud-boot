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

        $this->writeToFile($apollo);
        return $response->withJson(['status' => 'OK']);
    }


    private function writeToFile(ApolloUtil $apollo, $reties = 3)
    {
        if ($reties < 0) {
            LogFacade::warn("Apollo writeToFile. exceeds the maximum retries");
            return;
        }

        $configAll = array_merge($apollo->get('system'), $apollo->get('application'));
        if (empty($configAll)) {
            LogFacade::warn("Apollo writeToFile. the configAll empty");
            return $this->writeToFile($apollo, --$reties);
        }

        // write to file
        $res = file_put_contents(Env::APOLLO_FILE, Yaml::dump($configAll));
        if (false === $res) {
            LogFacade::warn("Apollo writeToFile. write return false");
            return $this->writeToFile($apollo, --$reties);
        }
    }


}