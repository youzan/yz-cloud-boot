<?php

// 推定的工程目录
$assumedAppDir = realpath(__DIR__ . '/../../../..');

// 检查是在项目运行还是独立运行(例如测试)，判断依据是 composer.json 和 env.php
if (file_exists($assumedAppDir . '/composer.json') and file_exists($assumedAppDir . '/config/env.php')) {
    require_once($assumedAppDir . '/config/env.php');
}

// 如果是项目运行，加载项目目录下 vendor 的 autoload, 否则在自身目录下面寻找 vendor 下的 autoload
if (defined('YZCLOUD_BOOT_APP_DIR')) {
    require_once(YZCLOUD_BOOT_APP_DIR . '/vendor/autoload.php');
} else {
    require_once(__DIR__ . '/../vendor/autoload.php');
}

// 初始化容器
/** @var \Psr\Container\ContainerInterface $container */
$container = YouzanCloudBoot\Boot\Bootstrap::setupContainer();

$app = new \Slim\App($container);

// 初始化应用
\YouzanCloudBoot\Boot\Bootstrap::setupApp($app);

if (defined('YZCLOUD_BOOT_APP_DIR')) {
    // 这里使用匿名函数保证上下文干净，避免污染当前文件的变量

    $requireRoutes = function () use ($app) {
        require_once(YZCLOUD_BOOT_APP_DIR . '/config/routes.php');
    };
    $requireRoutes();

    $reg = $container->get("beanRegistry");
    $requireBEPs = function () use ($reg) {
        require_once(YZCLOUD_BOOT_APP_DIR . '/config/beps.php');
    };
    $requireBEPs();
}

try {
    if ((!$reg instanceof \YouzanCloudBoot\Bep\BeanRegistry) or (!$app instanceof \Slim\App)) {
        //在 Userland 有可能被修改了实例，抛异常
        throw new Exception();
    }

    $app->run();
} catch (Exception $e) {
    // do something
}

