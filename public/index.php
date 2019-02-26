<?php

$assumedAppDir = realpath(__DIR__ . '/../../../..');

if (file_exists($assumedAppDir. '/composer.json') and file_exists($assumedAppDir . '/config/env.php')) {
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

try {
    $app->run();
} catch (Exception $e) {
    // do something
}

