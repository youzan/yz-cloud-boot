<?php

namespace YouzanCloudBoot\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use YouzanCloudBoot\Exception\ExtensionPointHandleException;
use YouzanCloudBoot\Helper\BeanRegistry;

class ExtensionPointController extends BaseController
{

    public function handle(Request $request, Response $response, array $args)
    {
        if (!array_key_exists('point', $args) or !array_key_exists('method', $args)) {
            throw new ExtensionPointHandleException('Error request');
        }

        $extPoint = $args['point'];
        $method = $args['method'];

        /** @var BeanRegistry $beanRegistry */
        $beanRegistry = $this->container->get('beanRegistry');

        $bean = $beanRegistry->getBean($extPoint);

        //FIXME 需要加入参数转换
        $bean->$method();

        return $response;
    }

}