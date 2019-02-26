<?php

namespace YouzanCloudBoot\Boot;

use Slim\App;
use Slim\Container;
use YouzanCloudBoot\Controller\ExtensionPointController;
use YouzanCloudBoot\Helper\BeanRegistry;

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

    public static function setupBEPs(App $app)
    {
        $app->any("/_bep/{point}/{method}", [ExtensionPointController::class, 'handle']);
    }

}