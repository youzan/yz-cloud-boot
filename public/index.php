<?php

require(__DIR__ . '/../vendor/autoload.php');

const YOUZAN_CLOUD_APP_DIR = __DIR__ . '/../../../..';

if (file_exists(YOUZAN_CLOUD_APP_DIR . '/composer.json')) {
    define('IN_YOUZAN_CLOUD_APP', true);
} else {
    define('IN_YOUZAN_CLOUD_APP', false);
}

$container = YouzanCloudBoot\Boot\Bootstrap::setupContainer();

$app = new \Slim\App($container);

\YouzanCloudBoot\Boot\Bootstrap::setupBEPs($app);

try {
    $app->run();
} catch (Exception $e) {
    // do something
}

