<?php

namespace YouzanCloudBootTests\Controller;

use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;
use YouzanCloudBootTests\Base\BaseTestCase;
use YouzanCloudBootTests\Stub\ExtensionPoint\BizTestService;
use YouzanCloudBootTests\Stub\ExtensionPoint\Param\BizTestOutParam;
use YouzanCloudBootTests\Stub\FakeServiceImpl;

class BusinessExtensionPointControllerTest extends BaseTestCase
{

    public function generateEnv()
    {
        $env = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/business-extension-point/youzanCloudBootTests.stub.ExtensionPoint.bizTestService/invoke',
            'SERVER_NAME' => 'localhost',
            'CONTENT_TYPE' => 'application/json',
            'HTTP_BEAN_NAME' => 'testBean',
            'HTTP_BEAN_TAG' => '',
        ]);

        $request = Request::createFromEnvironment($env);
        $request->getBody()->write('{}');

        $response = new Response();

        return [
            [$request, $response]
        ];
    }

    /**
     * @param $request
     * @param $response
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     * @dataProvider generateEnv
     */
    public function test(Request $request, Response $response)
    {
        $app = $this->getApp();

        /** @var \YouzanCloudBoot\ExtensionPoint\BeanRegistry $reg */
        $reg = $app->getContainer()->get('beanRegistry');
        $reg->registerBean('testBean', FakeServiceImpl::class);

        $result = $app($request, $response);

        $this->assertInstanceOf(BizTestOutParam::class, $result);
    }

}