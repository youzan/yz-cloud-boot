<?php

namespace YouzanCloudBoot\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use YouzanCloudBoot\Exception\ExtensionPointHandleException;

class ExtensionPointController extends BaseController
{

    public function handle(Request $request, Response $response, array $args)
    {
        if (!array_key_exists('point', $args) or !array_key_exists('method', $args)) {
            throw new ExtensionPointHandleException('Error request');
        }

        $extPoint = $args['point'];
        $method = $args['method'];

        /** @var \YouzanCloudBoot\Bep\BeanRegistry $beanRegistry */
        $beanRegistry = $this->getContainer()->get('beanRegistry');

        $beanInstance = $beanRegistry->getBean($extPoint);

        $result = $this->callMethod($beanInstance, $method, $request->getParsedBody());

        return $response->withJson($result);
    }

    private function callMethod($beanInstance, $method, $body)
    {
        /**
         * TODO
         * 利用反射:
         * 1. 实例化请求参数
         * 2. 传递参数给目标类
         * 3. 获得结果并返回
         */

        return $beanInstance->$method();
    }

}