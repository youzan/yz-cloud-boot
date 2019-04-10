<?php

namespace YouzanCloudBoot\Controller;

use ReflectionClass;
use Slim\Http\Request;
use Slim\Http\Response;
use YouzanCloudBoot\Component\BaseComponent;
use YouzanCloudBoot\Exception\TopicRegistryFailureException;
use YouzanCloudBoot\ExtensionPoint\Api\Message\MessageHandler;
use YouzanCloudBoot\ExtensionPoint\Api\Message\Metadata\NotifyMessage;
use YouzanCloudBoot\Traits\ExtensionPointUtil;

class MessageExtensionPointController extends BaseComponent
{

    use ExtensionPointUtil;

    public function handle(Request $request, Response $response, array $args)
    {

        $body = $request->getParsedBody();

        $objectBuilder = $this->getContainer()->get('objectBuilder');
        $parameter = $objectBuilder->convertArrayToObjectInstance($body, NotifyMessage::class);

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

        $msgMethodName = 'handle';

        $ref = new ReflectionClass($topicInstance);

        $this->assertInterfaceImplemented($ref, MessageHandler::class);

        $method = $ref->getMethod($msgMethodName);

        $invokeResult = $method->invoke($topicInstance, $parameter);
        return $invokeResult;
    }

}