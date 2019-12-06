<?php

namespace YouzanCloudBoot\Boot;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\SocketHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\ProcessIdProcessor;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Container;
use Slim\Http\Environment;
use Slim\Http\Uri;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use YouzanCloudBoot\Controller\ApolloController;
use YouzanCloudBoot\Controller\BusinessExtensionPointController;
use YouzanCloudBoot\Controller\HealthController;
use YouzanCloudBoot\Controller\HeartbeatController;
use YouzanCloudBoot\Controller\MessageExtensionPointController;
use YouzanCloudBoot\Exception\CommonException;
use YouzanCloudBoot\Exception\Handler\ErrorHandler;
use YouzanCloudBoot\ExtensionPoint\BepRegistry;
use YouzanCloudBoot\ExtensionPoint\MepRegistry;
use YouzanCloudBoot\Http\HttpClientWrapper;
use YouzanCloudBoot\Log\HostnameProcessor;
use YouzanCloudBoot\Log\YouzanLogger;
use YouzanCloudBoot\Log\YouzanSkynetProcessor;
use YouzanCloudBoot\Store\PDOFactory;
use YouzanCloudBoot\Store\RedisFactory;
use YouzanCloudBoot\Util\ApolloUtil;
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
        //系统异常
        $container['phpErrorHandler'] = function (ContainerInterface $container) {
            return new ErrorHandler();
        };
        $container['envUtil'] = function (ContainerInterface $container) {
            return new EnvUtil($container);
        };
        $container['logger'] = function (ContainerInterface $container) {
            /** @var EnvUtil $envUtil */
            $envUtil = $container->get('envUtil');
            $applicationName = $envUtil->getAppName();
            $logger = new YouzanLogger($applicationName);

            //控制台输出
            $logger->pushHandler(new StreamHandler('php://stdout', Logger::INFO));

            if ($applicationName != 'Youzan-Cloud-Boot-App') {
                $dateFormat = "Y-m-d H:i:s";
                $output = "<158>%datetime% %extra.hostname%/%extra.ip% %extra.level_name%[%extra.process_id%]: topic=log.%extra.app_name%.%extra.index_name% %extra.skynet_log%\n";
                $formatter = new LineFormatter($output, $dateFormat);

                $pidProcessor = new ProcessIdProcessor();
                $hostProcessor = new HostnameProcessor();
                $youzanSkynetProcessor = new YouzanSkynetProcessor($container);

                $socketHandler = new SocketHandler('tcp://' . $envUtil->get('logging.track.host') . ':5140', Logger::INFO);
                $socketHandler->setFormatter($formatter);
                $socketHandler->pushProcessor($pidProcessor);
                $socketHandler->pushProcessor($hostProcessor);
                $socketHandler->pushProcessor($youzanSkynetProcessor);
                $logger->pushHandler($socketHandler);
            }

            // 异常捕获
            $logger->setExceptionHandler(function (\Exception $e, array $record) {

            });

            return $logger;
        };
        $container['bepRegistry'] = function (ContainerInterface $container) {
            return new BepRegistry($container);
        };
        $container['objectBuilder'] = function (ContainerInterface $container) {
            return new ObjectBuilder($container);
        };
        $container['mepRegistry'] = function (ContainerInterface $container) {
            return new MepRegistry($container);
        };
        $container['pdoFactory'] = function (ContainerInterface $container) {
            return new PDOFactory($container);
        };
        $container['redisFactory'] = function (ContainerInterface $container) {
            return new RedisFactory($container);
        };
        $container['httpClient'] = function (ContainerInterface $container) {
            return new HttpClientWrapper($container);
        };
        $container['apolloUtil'] = function (ContainerInterface $container) {
            return new ApolloUtil($container);
        };
        $container['yzcMysql'] = function (ContainerInterface $container) {
            return $container->get('pdoFactory')->buildBuiltinMySQLInstance();
        };
        $container['yzcRedis'] = function (ContainerInterface $container) {
            return $container->get('redisFactory')->buildBuiltinRedisInstance();
        };
        $container['view'] = function (ContainerInterface $container) {
            if (!defined('YZCLOUD_BOOT_APP_DIR')) {
                throw new CommonException('YZCLOUD_BOOT_APP_DIR undefined');
            }
            $view = new Twig(YZCLOUD_BOOT_APP_DIR . '/templates');
            $view->addExtension(new TwigExtension($container->get('router'), Uri::createFromEnvironment(new Environment($_SERVER))));
            return $view;
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
            "/message-extension-point/com.youzan.cloud.base.service.api.MessageService/handleMessage",
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

        //拉取统一资源配置(Apollo)
        $app->get(
            "/apollo",
            ApolloController::class . ':handle'
        );
    }

}