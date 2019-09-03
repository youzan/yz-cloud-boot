<?php

namespace YouzanCloudBoot\Controller;

use ReflectionClass;
use Slim\Http\Request;
use Slim\Http\Response;
use YouzanCloudBoot\Component\BaseComponent;
use YouzanCloudBoot\Exception\TopicRegistryFailureException;
use YouzanCloudBoot\ExtensionPoint\Api\Message\MessageHandler;
use YouzanCloudBoot\Facades\LogFacade;
use YouzanCloudBoot\Traits\ExtensionPointUtil;

class MessageExtensionPointController extends BaseComponent
{

    use ExtensionPointUtil;

    public function handle(Request $request, Response $response, array $args)
    {

        $body = $request->getParsedBody();

        if (empty($body) || !isset($body['topic']) || !isset($body['data'])) {
            throw new TopicRegistryFailureException(
                'Body is empty'
            );
        }

        /** @var \YouzanCloudBoot\Util\ObjectBuilder $objectBuilder */
        $objectBuilder = $this->getContainer()->get('objectBuilder');

        /** @var \YouzanCloudBoot\ExtensionPoint\Api\Message\Metadata\NotifyMessage $parameter */
        $parameter = $objectBuilder->convertArrayToObjectInstance($body, new ReflectionClass('YouzanCloudBoot\ExtensionPoint\Api\Message\Metadata\NotifyMessage'));

        $topic = $parameter->getTopic();
        if (empty($topic)) {
            throw new TopicRegistryFailureException(
                'Topic is empty'
            );
        }

        /** @var \YouzanCloudBoot\ExtensionPoint\MepRegistry $mepRegistry */
        $mepRegistry = $this->getContainer()->get('mepRegistry');
        if (!$mepRegistry->checkTopicDefinitionExists($topic)) {
            LogFacade::warn('Not Implemented Message Extension Point: ' . $topic);
            // 针对: 订阅了消息扩展点 但是没有实现. 不抛异常 不重试
            return $response->withJson([
                'code' => 200,
                'message' => 'Not Implemented Message Extension Point: ' . $topic,
                'success' => true
            ]);
        }

        $topicInstance = $mepRegistry->getBean($topic);

        $this->callMethod($topicInstance, $parameter);

        $result = [];
        $result['code'] = 200;
        $result['message'] = 'Message Extension Point: ' . $topic . ' call success';
        $result['success'] = true;
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