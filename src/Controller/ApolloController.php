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

        $res = $this->writeToFile($apollo);
        return $response->withJson(['status' => $res]);
    }


    private function writeToFile(ApolloUtil $apollo, $reties = 3): string
    {
        if ($reties < 0) {
            return 'Fail, Apollo writeToFile. exceeds the maximum retries';
        }

        $configAll = array_merge($apollo->get('system'), $apollo->get('application'));
        if (empty($configAll)) {
            return $this->writeToFile($apollo, --$reties);
        }

        // write to file
        $res = file_put_contents(Env::APOLLO_FILE, Yaml::dump($configAll));
        if (false === $res) {
            return $this->writeToFile($apollo, --$reties);
        }

        return 'OK';
    }


}