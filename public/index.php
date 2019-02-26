<?php

require(__DIR__ . '/../vendor/autoload.php');

$container = YouzanCloudBoot\Boot\Bootstrap::setupContainer();

$app = new \Slim\App($container);

\YouzanCloudBoot\Boot\Bootstrap::setupBEPs($app);

try {
    $app->run();
} catch (Exception $e) {
    // do something
}

