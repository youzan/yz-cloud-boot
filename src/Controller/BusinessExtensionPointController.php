<?php

namespace YouzanCloudBoot\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use YouzanCloudBoot\Exception\ExtensionPointHandleException;

class BusinessExtensionPointController extends BaseController
{

    public function handle(Request $request, Response $response, array $args)
    {
        if (!array_key_exists('point', $args) or !array_key_exists('method', $args)) {
            throw new ExtensionPointHandleException('Error request');
        }

        $serviceName = $args['service'];
        $methodName = $args['method'];

        /** @var \YouzanCloudBoot\ExtensionPoint\BeanRegistry $beanRegistry */
        $beanRegistry = $this->getContainer()->get('beanRegistry');

        $beanName = $request->getHeader('Bean-Name');
        $beanTag = $request->getHeader('Bean-Tag');

        $beanInstance = $beanRegistry->getBean($beanName, $beanTag);

        $result = $this->callMethod($beanInstance, $serviceName, $methodName, $request->getParsedBody());

        return $response->withJson($result);
    }

    private function callMethod($beanInstance, $serviceName, $methodName, $body)
    {
        /**
         * TODO
         * 利用反射:
         * 1. 实例化请求参数
         * 2. 传递参数给目标类
         * 3. 获得结果并返回
         */

        return $beanInstance->$methodName();
    }

}