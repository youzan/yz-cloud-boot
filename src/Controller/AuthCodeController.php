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
        $code = $args['code'] ?? null;
        if (empty($code) || !is_string($code)) {
            return $response->withJson(['message' => 'code empty']);
        }

        /** @var TokenUtil $tokenUtil */
        $tokenUtil = $this->getContainer()->get('tokenUtil');
        $tokenArr = $tokenUtil->code2Token($code);
        if (!is_array($tokenArr) || !array_key_exists('access_token', $tokenArr)) {
            return $response->withJson(['message' => 'fail', $tokenArr]);
        }

        return $response->withJson(['message' => 'success']);
    }

}