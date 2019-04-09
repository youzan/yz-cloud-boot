<?php

namespace YouzanCloudBootTests\Database;

use YouzanCloudBoot\Database\PDOFactory;
use YouzanCloudBootTests\Base\BaseTestCase;

class PDOFactoryTest extends BaseTestCase
{

    private static $port;
    private static $dataDir;
    private static $pid;

    private static function commandExist($cmd)
    {
        $return = shell_exec(sprintf("which %s", escapeshellarg($cmd)));
        return !empty($return);
    }

    private static function getMySQLVersion()
    {
        $version = trim(shell_exec('mysql_config --version'));
        return $version;
    }

    private static function delTree($dir)
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

    private static function initDb($dir)
    {
        return shell_exec('mysqld --default-authentication-plugin=mysql_native_password --initialize-insecure --datadir=' . $dir . ' >/dev/null 2>/dev/null');
    }

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $installed = self::commandExist('mysql_config');

        if (!$installed) {
            self::markTestSkipped('MySQL is not installed');
        }
        $version = self::getMySQLVersion();
        self::assertNotEmpty($version);

        self::$port = rand(50000, 60000);
        self::$dataDir = '/tmp/mysql_temp_' . self::$port . '/';
        if (file_exists(self::$dataDir)) {
            self::delTree(self::$dataDir);
        }

        $initData = self::initDb(self::$dataDir);

        $cli = 'mysqld ';
        $cli .= '--port=' . self::$port . ' --datadir=' . self::$dataDir . ' --socket=' . self::$dataDir . 'local.sock'
            . self::$port . '.sock --pid-file=' . self::$dataDir . 'local.pid --default-authentication-plugin=mysql_native_password';

        if ($version >= '5.7.12') {
            //https://dev.mysql.com/doc/refman/5.7/en/x-plugin-options-system-variables.html
            $cli .= ' --mysqlx=off';
        }

        $cli .= ' >/dev/null 2>/dev/null & echo $!';
        exec($cli, $output);
        self::$pid = trim($output[0]);

        $_SERVER['mysql_host'] = '127.0.0.1';
        $_SERVER['mysql_port'] = self::$port;
        $_SERVER['mysql_username'] = 'root';
        $_SERVER['mysql_password'] = '';
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();

        $pid = self::$pid;
        if (empty($pid)) {
            $pid = trim(file_get_contents(self::$dataDir . 'local.pid'));
        }
        if ($pid) {
            posix_kill($pid, SIGTERM);
        }

        if (file_exists(self::$dataDir)) {
            @self::delTree(self::$dataDir);
        }
    }


    public function testBuild()
    {
        /** @var PDOFactory $pdoFactory */
        $pdoFactory = $this->getApp()->getContainer()->get('pdoFactory');

        $microSeconds = 0;
        while ($microSeconds < 10 * 1000000) {
            $pid = @trim(file_get_contents(self::$dataDir . 'local.pid'));
            if (!empty($pid)) {
                break;
            }
            $microSeconds += 500000;
            usleep(500000);
        }

        $pdo = $pdoFactory->buildBuiltinMySQLInstance();
        $this->assertNotNull($pdo);

        $stmt = $pdo->prepare('create database `test_creation`');
        $r = $stmt->execute();
        $this->assertTrue($r);
    }

}