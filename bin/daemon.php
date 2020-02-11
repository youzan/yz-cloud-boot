<?php

require_once(__DIR__ . '/../boot/functions.php');

init();

// init
$container = (function (): \Psr\Container\ContainerInterface {

    // set slim app
    $container = \YouzanCloudBoot\Boot\Bootstrap::setupContainer();
    $container['intervalTimerRegistry'] = function (\Psr\Container\ContainerInterface $container) {
        return new \YouzanCloudBoot\Daemon\Registry\IntervalTimerRegistry($container);
    };

    \YouzanCloudBoot\Facades\Facade::setFacadeApplication(new \Slim\App($container));
    return $container;
})();

// timing task workers
$intervalTimerWorker = new \Workerman\Worker();
$intervalTimerWorker->count = 1;

$intervalTimerWorker->onWorkerStart = function ($intervalTimerWorker) use ($container) {
    (new \YouzanCloudBoot\Daemon\Worker\IntervalTimerWorker($container))->onWorkerStart($intervalTimerWorker);
};

// start
\Workerman\Worker::runAll();
