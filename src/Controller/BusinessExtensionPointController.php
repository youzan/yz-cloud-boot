<?php

namespace YouzanCloudBoot\Controller;

use ReflectionClass;
use Slim\Http\Request;
use Slim\Http\Response;
use YouzanCloudBoot\Component\BaseComponent;
use YouzanCloudBoot\Exception\ExtensionPointHandleException;
use YouzanCloudBoot\Traits\ClassValidator;
use YouzanCloudBoot\Util\ObjectBuilder;

class BusinessExtensionPointController extends BaseComponent
{

    use ClassValidator;

    public function handle(Request $request, Response $response, array $args)
    {
        if (!array_key_exists('service', $args) or !array_key_exists('method', $args)) {
            throw new ExtensionPointHandleException('Error request');
        }

        $serviceName = $args['service'];
        $methodName = $args['method'];

        /** @var \YouzanCloudBoot\ExtensionPoint\BeanRegistry $beanRegistry */
        $beanRegistry = $this->getContainer()->get('beanRegistry');

        $beanName = $request->getHeaderLine('Bean-Name');
        $beanTag = $request->getHeaderLine('Bean-Tag');

        $beanInstance = $beanRegistry->getBean($beanName, $beanTag);

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
        $interfaces = $ref->getInterfaces();

        //判断该实现类是否实现了对应的接口
        $interfaceMatch = false;
        foreach ($interfaces as $interface) {
            if ($interface->getName() == $serviceInterfaceName) {
                $interfaceMatch = true;
            }
        }

        if ($interfaceMatch == false) {
            throw new ExtensionPointHandleException(
                'Interface [' . $serviceInterfaceName . '] not implemented in class [' . $ref->getName() . ']'
            );
        }

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

    /**
     * 将 Java 风格的类名转换为 PHP UpperCamelCase 风格
     *
     * @param $serviceName
     * @return string
     * @throws ExtensionPointHandleException
     */
    private function parseServiceInterfaceName($serviceName)
    {
        $serviceNameParts = explode(".", $serviceName);
        $serviceNamePartsInUpperCamelCase = array_map(function ($item) {
            return ucfirst($item);
        }, $serviceNameParts);
        $serviceInterfaceName = implode('\\', $serviceNamePartsInUpperCamelCase);

        if (empty($serviceInterfaceName)) {
            throw new ExtensionPointHandleException('Error request [interface name error]');
        }

        return $serviceInterfaceName;
    }


}