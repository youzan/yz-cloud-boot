<?php

namespace YouzanCloudBoot\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use YouzanCloudBoot\Component\BaseComponent;
use YouzanCloudBoot\Util\TokenUtil;

class AuthCodeController extends BaseComponent
{

    public function handle(Request $request, Response $response, array $args)
    {
        $code = $request->getParam('code');
        if (empty($code) || !is_string($code)) {
            return $response->withJson(['message' => 'code empty']);
        }

        /** @var TokenUtil $tokenUtil */
        $tokenUtil = $this->getContainer()->get('tokenUtil');
        $tokenUtil->code2Token($code);

        return $response->withJson(['message' => 'success']);
    }

}