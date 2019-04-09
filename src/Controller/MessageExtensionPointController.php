<?php

namespace YouzanCloudBoot\Controller;

use ReflectionClass;
use Slim\Http\Request;
use Slim\Http\Response;
use YouzanCloudBoot\Component\BaseComponent;
use YouzanCloudBoot\Exception\TopicRegistryFailureException;
use YouzanCloudBoot\Traits\ClassValidator;

class MessageExtensionPointController extends BaseComponent
{
    use ClassValidator;

    public function handle(Request $request, Response $response, array $args)
    {

        $ref = new ReflectionClass('\Com\Youzan\Cloud\Extension\Param\NotifyMessage');
        $body = $request->getParsedBody();

        $objectBuilder = $this->getContainer()->get('objectBuilder');
        $parameter = $objectBuilder->convertArrayToObjectInstance($body, $ref);

        $topic = $parameter->getTopic();
        if (empty($topic)) {
            throw new TopicRegistryFailureException(
                'Topic is empty'
            );
        }

        $topicRegistry = $this->getContainer()->get('topicRegistry');
        $topicInstance = $topicRegistry->getBean($topic);

        $result = $this->callMethod($topicInstance, $parameter);

        return $response->withJson($result);
    }

    private function callMethod($topicInstance, $parameter)
    {
        /**
         * 利用反射:
         * \Com\Youzan\Cloud\Extension\Api\Message\MessageHandler
         * 1. 实例化请求参数
         * 2. 传递参数给目标类
         * 3. 获得结果并返回
         */

        $msgInterfaceName = 'Com\Youzan\Cloud\Extension\Api\Message\MessageHandler';
        $msgMethodName = 'handle';

        $this->assertInterfaceExists($msgInterfaceName, true);

        $ref = new ReflectionClass($topicInstance);
        $interfaces = $ref->getInterfaces();

        //判断该实现类是否实现了对应的接口
        $interfaceMatch = false;
        foreach ($interfaces as $interface) {
            if ($interface->getName() == $msgInterfaceName) {
                $interfaceMatch = true;
            }
        }

        if ($interfaceMatch == false) {
            throw new TopicRegistryFailureException(
                'Interface [' . $msgInterfaceName . '] not implemented in class [' . $ref->getName() . ']'
            );
        }

        if (!$ref->hasMethod($msgMethodName)) {
            throw new TopicRegistryFailureException('Called wrong method [handle]');
        }

        $method = $ref->getMethod($msgMethodName);

        $invokeResult = $method->invoke($topicInstance, $parameter);
        return $invokeResult;
    }

}