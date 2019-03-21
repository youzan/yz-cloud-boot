<?php

namespace YouzanCloudBoot\Boot;

use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Container;
use YouzanCloudBoot\Controller\BusinessExtensionPointController;
use YouzanCloudBoot\Controller\MessageExtensionPointController;
use YouzanCloudBoot\ExtensionPoint\BeanRegistry;
use YouzanCloudBoot\Helper\ObjectScrewDriver;

class Bootstrap
{

    public static function setupContainer(): ContainerInterface
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
        $container['objectScrewDriver'] = function ($container) {
            return new ObjectScrewDriver($container);
        };

        return $container;
    }

    public static function setupApp(App $app): void
    {
        //业务扩展点
        $app->post(
            "/business-extension-point/{service}/{method}",
            BusinessExtensionPointController::class . ':handle'
        );

        //消息扩展点
        $app->post(
            "/message-extension-point/com.youzan.cloud.extension.api.message.MessageHandler/handle",
            MessageExtensionPointController::class . ':handle'
        );
    }

}