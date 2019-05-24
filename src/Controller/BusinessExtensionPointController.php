<?php

namespace YouzanCloudBoot\Controller;

use ReflectionClass;
use Slim\Http\Request;
use Slim\Http\Response;
use YouzanCloudBoot\Component\BaseComponent;
use YouzanCloudBoot\Exception\ExtensionPointHandleException;
use YouzanCloudBoot\Traits\ExtensionPointUtil;
use YouzanCloudBoot\Util\ObjectBuilder;

class BusinessExtensionPointController extends BaseComponent
{

    use ExtensionPointUtil;

    public function handle(Request $request, Response $response, array $args)
    {
        if (!array_key_exists('service', $args) or !array_key_exists('method', $args)) {
            throw new ExtensionPointHandleException('Error request');
        }

        $serviceName = $args['service'];
        $methodName = $args['method'];

        /** @var \YouzanCloudBoot\ExtensionPoint\BepRegistry $bepRegistry */
        $bepRegistry = $this->getContainer()->get('bepRegistry');

        $beanName = $request->getHeaderLine('Bean-Name');
        $beanTag = $request->getHeaderLine('Bean-Tag');

        $beanInstance = $bepRegistry->getBean($beanName, $beanTag);

        $result = $this->callMethod($beanInstance, $serviceName, $methodName, $request->getParsedBody());

        return $response->withJson($result);
    }

    private function callMethod($beanInstance, $serviceName, $methodName, $body)
    {
        /**
         * 利用反射:
         * serviceName : com.youzan.cloud.extension.api.BizTestService
         * 需要转成 Com\Youzan\Cloud\Extension\Api\BizTestService
         * 1. 实例化请求参数
         * 2. 传递参数给目标类
         * 3. 获得结果并返回
         */

        //获取接口全类名
        $serviceInterfaceName = $this->parseServiceInterfaceName($serviceName);

        $this->assertInterfaceExists($serviceInterfaceName, true);

        $ref = new ReflectionClass($beanInstance);

        $this->assertInterfaceImplemented($ref, $serviceInterfaceName);

        if (!$ref->hasMethod($methodName)) {
            throw new ExtensionPointHandleException('Called wrong method [' . $methodName . ']');
        }

        $method = $ref->getMethod($methodName);

        /** @var ObjectBuilder $objectBuilder */
        $objectBuilder = $this->getContainer()->get('objectBuilder');

        $parameter = $objectBuilder->convertArrayToMethodExclusiveParam($method, $body);

        $invokeResult = $method->invoke($beanInstance, $parameter);
        return $invokeResult;
    }


}