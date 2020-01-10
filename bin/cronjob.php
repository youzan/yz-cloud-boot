<?php

use Psr\Container\ContainerInterface;
use Slim\App;
use Workerman\Worker;
use YouzanCloudBoot\Boot\Bootstrap;
use YouzanCloudBoot\CronJob\Registry\TimingRegistry;
use YouzanCloudBoot\CronJob\Worker\TimingWorker;
use YouzanCloudBoot\Facades\Facade;

require_once __DIR__ . '/../base/app.php';

// init
$container = (function (): ContainerInterface {

    $assumedAppDir = assumedAppDir();

    // 检查是在项目运行还是独立运行(例如测试)，判断依据是 composer.json 和 env.php
    if (file_exists($assumedAppDir . '/composer.json')) {
        if (file_exists($assumedAppDir . '/config/env.php')) {
            require_once($assumedAppDir . '/config/env.php');
        }
    }

    // 如果是项目运行，加载项目目录下 vendor 的 autoload, 否则在自身目录下面寻找 vendor 下的 autoload
    if (defined('YZCLOUD_BOOT_APP_DIR')) {
        require_once(YZCLOUD_BOOT_APP_DIR . '/vendor/autoload.php');
    } else {
        require_once(__DIR__ . '/../vendor/autoload.php');
    }

    // set slim app
    $container = Bootstrap::setupContainer();
    $container['timingRegistry'] = function (ContainerInterface $container) {
        return new TimingRegistry($container);
    };
    Facade::setFacadeApplication(new App($container));

    if (defined('YZCLOUD_BOOT_APP_DIR')) {
        if (file_exists(YZCLOUD_BOOT_APP_DIR . '/config/timing.php')) {
            $timingReg = $container->get("timingRegistry");
            (function () use ($timingReg) {
                require(YZCLOUD_BOOT_APP_DIR . '/config/timing.php');
            })();
        }
    }

    return $container;
})();

// timing task workers
$timingWorker = new Worker();
$timingWorker->count = (function (): int {

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

$timingWorker->onWorkerStart = function ($timingWorker) use ($container) {
    (new TimingWorker($container))->onWorkerStart($timingWorker);
};

// start
Worker::runAll();
