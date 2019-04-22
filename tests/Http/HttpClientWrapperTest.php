<?php

namespace YouzanCloudBootTests\Http;

use YouzanCloudBoot\Http\HttpClientFactory;
use YouzanCloudBootTests\Base\BaseTestCase;

class HttpClientWrapperTest extends BaseTestCase
{

    public function testWithoutProxy()
    {
        /** @var HttpClientFactory $factory */
        $factory = $this->getApp()->getContainer()->get('httpClientFactory');

        $client = $factory->buildHttpClient();
        $r = $client->get('http://www.baidu.com');

        $this->assertRegExp('/百度/', $r);
    }


    public function startServer()
    {
        self::runInUnixLike();
        if (!self::commandExist('php')) {
            $this->markTestSkipped('PHP binary is not found');
        }

        $port = rand(61000, 62000);
        $server = 'localhost';
        $dataDir = sprintf('/tmp/php_temp_%s', $port);
        @mkdir($dataDir);
        $logFile = sprintf('%s/php_out.log', $dataDir);
        $pidFile = sprintf('%s/php.pid', $dataDir);
        $indexFile = realpath(__DIR__ . '/../Stub/EchoServer.php');
        $cli = sprintf('php -S %s:%s %s >%s 2>&1 & echo $!', $server, $port, $indexFile, $logFile);

        echo "\n**********\nStart a temporary php-dev-server with data dir at [${dataDir}]\nIf phpunit do not exit normally, you can remove it manually.\n**********\n";

        exec($cli, $output);
        $pid = trim($output[0]);
        file_put_contents($pidFile, $pid);

        $_SERVER['youzan_proxy_enable'] = 'true';
        $_SERVER['youzan_proxy_host'] = sprintf('%s:%s', $server, $port);
        $_SERVER['youzan_proxy_token'] = 'hello,world';
        $_SERVER['youzan_proxy_nonProxyHosts'] = '';

        @usleep(1000000);

        return [$server, $port, $pid, $dataDir];
    }

    public function stopServerAndCleanData($pid, $dataDir)
    {
        @usleep(1000000);

        if ($pid) {
            echo "\n**********\nKilling php-dev-server, pid: ${pid}\n**********\n";
            @posix_kill($pid, SIGTERM);
        }

        if ($dataDir and file_exists($dataDir)) {
            echo "\n**********\nClean up temporary data dir [${dataDir}] \n**********\n";
            @self::delTree($dataDir);
        }
    }


    public function testWithEchoServer()
    {
        list($server, $port, $pid, $dataDir) = $this->startServer();
        try {

            /** @var HttpClientFactory $factory */
            $factory = $this->getApp()->getContainer()->get('httpClientFactory');

            $client = $factory->buildHttpClient();
            $r = $client->get('http://www.test.com:1024/testPath?testQuery');

            $response = json_decode($r, true);

            $this->assertSame('1024', $response['HTTP_PORT']);
            $this->assertSame('http', $response['HTTP_SCHEME']);
            $this->assertSame('hello,world', $response['HTTP_YZC_TOKEN']);
            $this->assertSame('www.test.com', $response['HTTP_HOST']);

        } finally {
            $this->stopServerAndCleanData($pid, $dataDir);
        }
    }


}