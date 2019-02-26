<?php

namespace YouzanCloudBoot\Boot;

use Slim\App;
use Slim\Container;
use YouzanCloudBoot\Bep\BeanRegistry;
use YouzanCloudBoot\Controller\ExtensionPointController;

class Bootstrap
{

    public static function setupContainer()
    {
        $container = new Container();

        $container['logger'] = function ($container) {
            $logger = new \Monolog\Logger('my_logger');
            $handler = new \Monolog\Handler\StreamHandler('/tmp/test.log');
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
        $app->post("/_bep/{point}/{method}", ExtensionPointController::class . ':handle');
    }

}