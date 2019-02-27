<?php

namespace YouzanCloudBoot\Boot;

use Slim\App;
use Slim\Container;
use YouzanCloudBoot\ExtensionPoint\BeanRegistry;
use YouzanCloudBoot\Controller\BusinessExtensionPointController;

class Bootstrap
{

    public static function setupContainer()
    {
        $container = new Container();

        $container['logger'] = function ($container) {
            $logger = new \Monolog\Logger('yz-cloud-boot-app');
            $handler = new \Monolog\Handler\SyslogHandler('yz-cloud-boot-app');
            $logger->pushHandler($handler);
            return $logger;
        };
        $container['beanRegistry'] = function ($container) {
            return new BeanRegistry($container);
        };

        return $container;
    }

    public static function setupApp(App $app)
    {
        //业务扩展点
        $app->post(
            "/business-extension-point/{service}/{method}",
            BusinessExtensionPointController::class . ':handle'
        );

        //消息扩展点
        $app->post(
            "/message-extension-point/com.youzan.cloud.extension.api.message.MessageHandler/handle",
            BusinessExtensionPointController::class . ':handle'
        );
    }

}