<?php

namespace YouzanCloudBootTests\Store;

use YouzanCloudBoot\Store\RedisFactory;
use YouzanCloudBootTests\Base\BaseTestCase;

class RedisFactoryTest extends BaseTestCase
{

    private static $port;
    private static $dataDir;
    private static $pid;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        parent::runInUnixLike();

        $installed = self::commandExist('redis-server');

        if (!$installed) {
            self::markTestSkipped('Redis is not installed');
        }

        self::$port = rand(61000, 62000);
        self::$dataDir = '/tmp/redis_temp_' . self::$port;

        $dir = self::$dataDir;
        mkdir($dir);

        $configs = [
            sprintf('pidfile %s/redis.pid', $dir),
            sprintf('port %s', self::$port),
            sprintf('logfile %s/redis.log', $dir),
            sprintf('dir %s', $dir)
        ];

        echo "\n**********\nStart a temporary redis-server with data dir at [${dir}]\nIf phpunit do not exit normally, you can remove it manually.\n**********\n";
        $cli = sprintf("echo '%s' | redis-server -  >/dev/null 2>/dev/null & echo $!", implode("\n", $configs));
        exec($cli, $output);
        self::$pid = trim($output[0]);

        $_SERVER['spring_redis_host'] = 'localhost';
        $_SERVER['spring_redis_port'] = self::$port;
    }


    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();

        $pid = self::$pid;
        if (empty($pid)) {
            $pid = trim(file_get_contents(sprintf('%s/redis.pid', self::$dataDir)));
        }
        if ($pid) {
            echo "\n**********\nKilling redis-server, pid: ${pid}\n**********\n";
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
        $this->assertTrue(true);

        $microSeconds = 0;
        while ($microSeconds < 10 * 1000000) {
            $pid = @trim(file_get_contents(sprintf('%s/redis.pid', self::$dataDir)));
            if (!empty($pid)) {
                break;
            }
            $microSeconds += 500000;
            usleep(500000);
        }

        /** @var RedisFactory $redisFactory */
        $redisFactory = $this->getApp()->getContainer()->get('redisFactory');

        $redis = $redisFactory->buildBuiltinRedisInstance();
        $this->assertNotNull($redis);

        $redis->set('hello', 'world');

        $this->assertEquals('world', $redis->get('hello'));
    }


}