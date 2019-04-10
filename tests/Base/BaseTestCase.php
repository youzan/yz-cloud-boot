<?php

namespace YouzanCloudBootTests\Base;

require_once(__DIR__ . '/../../vendor/autoload.php');

use PHPUnit\Framework\TestCase;
use Slim\App;
use YouzanCloudBoot\Boot\Bootstrap;
use YouzanCloudBootTests\Store\PDOFactoryTest;

abstract class BaseTestCase extends TestCase
{

    protected static function delTree($dir)
    {
        $files = glob($dir . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (substr($file, -1) == '/') {
                self::delTree($file);
            } else {
                unlink($file);
            }
        }

        if (is_dir($dir)) {
            rmdir($dir);
        }
    }

    public function getApp()
    {
        $container = Bootstrap::setupContainer();

        $app = new App($container);

        Bootstrap::setupApp($app);
        return $app;
    }

    protected static function commandExist($cmd)
    {
        $return = shell_exec(sprintf("which %s", escapeshellarg($cmd)));
        return !empty($return);
    }
}