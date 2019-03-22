<?php

namespace YouzanCloudBootTests\Base;

require_once(__DIR__ . '/../../vendor/autoload.php');

use PHPUnit\Framework\TestCase;
use Slim\App;
use YouzanCloudBoot\Boot\Bootstrap;

abstract class BaseTestCase extends TestCase
{

    public function getApp()
    {
        $container = Bootstrap::setupContainer();

        $app = new App($container);

        Bootstrap::setupApp($app);
        return $app;
    }
}