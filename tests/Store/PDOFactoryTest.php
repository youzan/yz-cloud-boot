<?php

namespace YouzanCloudBootTests\Store;

use PDO;
use YouzanCloudBoot\Store\PDOFactory;
use YouzanCloudBootTests\Base\BaseTestCase;

class PDOFactoryTest extends BaseTestCase
{

    private static $port;
    private static $dataDir;
    private static $pid;

    private static function getMySQLVersion()
    {
        $version = trim(shell_exec('mysql_config --version'));
        return $version;
    }

    private static function initDb($dir)
    {
        echo "\n**********\nInitialize a temporary mysql with data dir at [${dir}]\nIf phpunit do not exit normally, you can remove it manually.\n**********\n";
        return shell_exec(
            sprintf(
                'mysqld --default-authentication-plugin=mysql_native_password --initialize-insecure --datadir=%s >/dev/null 2>/dev/null',
                self::$dataDir
            )
        );
    }

    private static function readPidFromLocal()
    {
        return @trim(file_get_contents(sprintf('%s/local.pid', self::$dataDir)));
    }

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        parent::runInUnixLike();

        $installed = self::commandExist('mysql_config');

        if (!$installed) {
            self::markTestSkipped('MySQL is not installed, skip test case: ' . __CLASS__);
        }
        $version = self::getMySQLVersion();
        self::assertNotEmpty($version);

        self::$port = rand(50000, 60000);
        self::$dataDir = '/tmp/mysql_temp_' . self::$port;
        if (file_exists(self::$dataDir)) {
            self::delTree(self::$dataDir);
        }

        $initData = self::initDb(self::$dataDir);

        $cli = sprintf(
            'mysqld --port=%s --datadir=%s --socket=%s --pid-file=%s --default-authentication-plugin=mysql_native_password',
            self::$port, self::$dataDir, sprintf('%s/local.sock', self::$dataDir), sprintf('%s/local.pid', self::$dataDir)
        );

        if ($version >= '5.7.12') {
            //https://dev.mysql.com/doc/refman/5.7/en/x-plugin-options-system-variables.html
            $cli .= ' --mysqlx=off';
        }

        $cli .= ' >/dev/null 2>/dev/null & echo $!';
        exec($cli, $output);
        self::$pid = @trim($output[0]);

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
            $pid = self::readPidFromLocal();
        }
        if ($pid) {
            echo "\n**********\nKilling mysqld, pid: ${pid}\n**********\n";
            posix_kill($pid, SIGTERM);
        }

        $dir = self::$dataDir;
        if (file_exists($dir)) {
            echo "\n**********\nClean up temporary data dir [${dir}] \n**********\n";
            @self::delTree($dir);
        }
    }


    public function test()
    {
        /** @var PDOFactory $pdoFactory */
        $pdoFactory = $this->getApp()->getContainer()->get('pdoFactory');

        $microSeconds = 0;
        while ($microSeconds < 10 * 1000000) {
            $pid = self::readPidFromLocal();
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

        /** @var PDO $yzcMysql */
        $yzcMysql = $this->getApp()->getContainer()->get('yzcMysql');
        $this->assertNotNull($yzcMysql);

        $stmt = $yzcMysql->prepare('drop database `test_creation`');
        $r = $stmt->execute();
        $this->assertTrue($r);
    }

}