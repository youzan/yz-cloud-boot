<?php

namespace YouzanCloudBootTests\Base;

require_once(__DIR__ . '/../../vendor/autoload.php');

use PHPUnit\Framework\TestCase;
use Slim\App;
use YouzanCloudBoot\Boot\Bootstrap;
use YouzanCloudBoot\Facades\Facade;

abstract class BaseTestCase extends TestCase
{
    protected $app;

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

    protected static function commandExist($cmd)
    {
        $return = shell_exec(sprintf("which %s", escapeshellarg($cmd)));
        return !empty($return);
    }

    /**
     * 仅允许某个测试在 Unix 下运行，非常不严谨，但够用
     */
    protected static function runInUnixLike()
    {
        $system = php_uname('s');

        if (!in_array($system, ['Darwin', 'Linux', 'FreeBSD'])) {
            self::markTestSkipped(get_called_class() . ' is not support on current system: ' . $system);
        }
    }

    public function getApp()
    {
        return $this->app;
    }

    public function setUp()
    {
        $container = Bootstrap::setupContainer();

        $app = new App($container);

        Bootstrap::setupApp($app);

        Facade::setFacadeApplication($app);

        $this->app = $app;
    }
}