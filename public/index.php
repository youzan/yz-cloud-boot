<?php

$assumedAppDir = realpath(__DIR__ . '/../../../..');

if (file_exists($assumedAppDir . '/composer.json') and file_exists($assumedAppDir . '/config/env.php')) {
    require_once($assumedAppDir . '/config/env.php');
}

if (defined('YZCLOUD_BOOT_APP_DIR')) {
    require_once(YZCLOUD_BOOT_APP_DIR . '/vendor/autoload.php');
} else {
    require_once(__DIR__ . '/../vendor/autoload.php');
}

$container = YouzanCloudBoot\Boot\Bootstrap::setupContainer();

$app = new \Slim\App($container);

\YouzanCloudBoot\Boot\Bootstrap::setupBEPs($app);

if (defined('YZCLOUD_BOOT_APP_DIR')) {
    $reg = $container->get("beanRegistry");

    // 这里使用匿名函数保证上下文干净，注入 beps.php 的只有 $reg 一个变量
    $requireBEPs = function () use ($reg) {
        require_once(YZCLOUD_BOOT_APP_DIR . '/config/beps.php');
    };
    $requireBEPs();

    $requireRoutes = function () use ($app) {
        require_once(YZCLOUD_BOOT_APP_DIR . '/config/routes.php');
    };
    $requireRoutes();
}

try {
    $app->run();
} catch (Exception $e) {
    // do something
}

