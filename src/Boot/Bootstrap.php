<?php

namespace YouzanCloudBoot\Boot;

use Monolog\Processor\ProcessIdProcessor;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Container;
use YouzanCloudBoot\Controller\BusinessExtensionPointController;
use YouzanCloudBoot\Controller\Error\ErrorHandler;
use YouzanCloudBoot\Controller\Health\HealthController;
use YouzanCloudBoot\Controller\HeartbeatController;
use YouzanCloudBoot\Controller\MessageExtensionPointController;
use YouzanCloudBoot\ExtensionPoint\BeanRegistry;
use YouzanCloudBoot\ExtensionPoint\TopicRegistry;
use YouzanCloudBoot\Log\HostnameProcessor;
use YouzanCloudBoot\Store\PDOFactory;
use YouzanCloudBoot\Store\RedisFactory;
use YouzanCloudBoot\Util\EnvUtil;
use YouzanCloudBoot\Util\ObjectBuilder;

class Bootstrap
{

    public static function setupContainer(): ContainerInterface
    {
        $container = new Container();

        $container['errorHandler'] = function (ContainerInterface $container) {
            return new ErrorHandler();
        };

        $container['logger'] = function (ContainerInterface $container) {
            $dateFormat = "Y-m-d H:i:s";
            $output = "<158>%datetime% %extra.hostname%/%extra.ip% %level_name% [%extra.process_id%]: topic=log.skynet-log.buyaodongplease {'tag':%message%}\n";
            $formatter = new \Monolog\Formatter\LineFormatter($output, $dateFormat);

            $pidProcessor = new ProcessIdProcessor();
            $hostProcessor = new HostnameProcessor();
            $logger = new \Monolog\Logger('yz-cloud-boot-app');
            $streamHandler = new \Monolog\Handler\StreamHandler('/Users/allen/php/yz-cloud-boot-demo-app/my_app.log', \Monolog\Logger::INFO);
            $streamHandler->setFormatter($formatter);
            $streamHandler->pushProcessor($pidProcessor);
            $streamHandler->pushProcessor($hostProcessor);
            $socketHandler = new \Monolog\Handler\SocketHandler('tcp://flume-qa.s.qima-inc.com:5140', \Monolog\Logger::INFO);
            $socketHandler->setFormatter($formatter);
            $socketHandler->pushProcessor($pidProcessor);
            $socketHandler->pushProcessor($hostProcessor);
            $logger->pushHandler($streamHandler);
            $logger->pushHandler($socketHandler);
            return $logger;
        };
        $container['beanRegistry'] = function (ContainerInterface $container) {
            return new BeanRegistry($container);
        };
        $container['objectBuilder'] = function (ContainerInterface $container) {
            return new ObjectBuilder($container);
        };
        $container['envUtil'] = function (ContainerInterface $container) {
            return new EnvUtil($container);
        };
        $container['topicRegistry'] = function (ContainerInterface $container) {
            return new TopicRegistry($container);
        };
        $container['pdoFactory'] = function (ContainerInterface $container) {
            return new PDOFactory($container);
        };
        $container['redisFactory'] = function (ContainerInterface $container) {
            return new RedisFactory($container);
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

        //心跳
        $app->post(
            "/_HB_",
            HeartbeatController::class . ':handle'
        );

        //健康检查
        $app->get(
            "/health",
            HealthController::class . ':handle'
        );
    }

}