<?php

(function () {
    require_once(__DIR__ . '/../boot/functions.php');
    init();
    developServer();
})();

// 初始化容器
/** @var \Psr\Container\ContainerInterface $container */
$container = YouzanCloudBoot\Boot\Bootstrap::setupContainer();

// 初始化应用
$app = new \Slim\App($container);
\YouzanCloudBoot\Boot\Bootstrap::setupApp($app);
\YouzanCloudBoot\Facades\Facade::setFacadeApplication($app);

if (defined('YZCLOUD_BOOT_APP_DIR')) {
    // 这里使用匿名函数保证上下文干净，避免污染当前文件的变量

    (function () use ($app) {
        require(YZCLOUD_BOOT_APP_DIR . '/config/routes.php');
    })();

    (function () use ($app) {
        if (file_exists(YZCLOUD_BOOT_APP_DIR . '/config/middlewares.php')) {
            require(YZCLOUD_BOOT_APP_DIR . '/config/middlewares.php');
        }
    })();

    $bepReg = $container->get("bepRegistry");
    (function () use ($bepReg) {
        require(YZCLOUD_BOOT_APP_DIR . '/config/beps.php');
    })();

    $mepReg = $container->get("mepRegistry");
    (function () use ($mepReg) {
        require(YZCLOUD_BOOT_APP_DIR . '/config/meps.php');
    })();
}

try {
    if ((isset($bepReg) and !$bepReg instanceof \YouzanCloudBoot\ExtensionPoint\BepRegistry)
        or (isset($mepReg) and !$mepReg instanceof \YouzanCloudBoot\ExtensionPoint\MepRegistry)
        or (!$app instanceof \Slim\App)) {
        //在 app 端有可能被修改实例，抛异常
        throw new Exception();
    }

    $app->run();
} catch (Exception $e) {
    // do something
}

