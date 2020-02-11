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

    if (defined('YZCLOUD_BOOT_APP_DIR')) {
        if (file_exists(YZCLOUD_BOOT_APP_DIR . '/config/intervalTimer.php')) {
            $intervalTimerRegistry = $container->get("intervalTimerRegistry");
            (function () use ($intervalTimerRegistry) {
                require(YZCLOUD_BOOT_APP_DIR . '/config/intervalTimer.php');
            })();
        }
    }

    return $container;
})();

// timing task workers
$intervalTimerWorker = new \Workerman\Worker();
$intervalTimerWorker->count = (function (): int {

    if (!defined('YZCLOUD_BOOT_TIMING_WORKER_COUNT')) {
        return 1;
    }

    $count = intval(YZCLOUD_BOOT_TIMING_WORKER_COUNT);

    if ($count < 1) {
        return 1;
    }
    if ($count > 10) {
        return 10;
    }

    return $count;
})();

$intervalTimerWorker->onWorkerStart = function ($intervalTimerWorker) use ($container) {
    (new \YouzanCloudBoot\Daemon\Worker\IntervalTimerWorker($container))->onWorkerStart($intervalTimerWorker);
};

// start
\Workerman\Worker::runAll();
