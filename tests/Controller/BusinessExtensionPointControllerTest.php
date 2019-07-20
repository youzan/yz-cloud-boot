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
        $env0 = Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/business-extension-point/youzanCloudBootTests.stub.ExtensionPoint.bizTestService/invoke',
            'SERVER_NAME' => 'localhost',
            'CONTENT_TYPE' => 'application/json',
            'HTTP_BEAN_NAME' => 'testBean',
            'HTTP_BEAN_TAG' => '',
        ]);
        $request0 = Request::createFromEnvironment($env0);
        $request0->getBody()->write('{}');
        $response0 = new Response();

        $env1 =Environment::mock([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/business-extension-point/youzanCloudBootTests.stub.ExtensionPoint.bizTestService/invoke',
            'SERVER_NAME' => 'localhost',
            'CONTENT_TYPE' => 'application/json',
            'HTTP_BEAN_NAME' => 'testBean',
            'HTTP_BEAN_TAG' => '1024',
        ]);
        $request1 = Request::createFromEnvironment($env1);
        $request1->getBody()->write('{}');
        $response1 = new Response();

        return [
            [$request0, $response0],
            [$request1, $response1]
        ];
    }

    /**
     * @param $request
     * @param $response
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     * @throws \YouzanCloudBoot\Exception\BeanRegistryFailureException
     * @dataProvider generateEnv
     */
    public function test(Request $request, Response $response)
    {
        $app = $this->getApp();

        /** @var \YouzanCloudBoot\ExtensionPoint\BepRegistry $bepReg */
        $bepReg = $app->getContainer()->get('bepRegistry');
        $bepReg->register('testBean', FakeServiceImpl::class);
        $bepReg->register('testBean', FakeServiceImpl::class, '1024');

        $result = $app($request, $response);

        // rewind response data stream
        $result->getBody()->rewind();

        $responseJson = json_decode($result->getBody()->getContents());

        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals(FakeServiceImpl::$r, $responseJson->code);
    }

}