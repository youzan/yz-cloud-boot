<?php

namespace YouzanCloudBootTests\Http;

use CURLFile;
use YouzanCloudBoot\Http\HttpClientWrapper;
use YouzanCloudBootTests\Base\BaseTestCase;

class HttpClientWrapperTest extends BaseTestCase
{

    private static $server;
    private static $port;
    private static $pid;
    private static $dataDir;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::runInUnixLike();
        if (!self::commandExist('php')) {
            self::markTestSkipped('PHP binary is not found');
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
        $_SERVER['youzan_proxy_host'] = sprintf('http://%s:%s', $server, $port);
        $_SERVER['youzan_proxy_token'] = 'hello,world';
        $_SERVER['youzan_proxy_nonProxyHosts'] = 'www.baidu.com';

        @usleep(1000000);

        self::$server = $server;
        self::$port = $port;
        self::$pid = $pid;
        self::$dataDir = $dataDir;
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();

        @usleep(1000000);

        $pid = self::$pid;
        $dataDir = self::$dataDir;

        if ($pid) {
            echo "\n**********\nKilling php-dev-server, pid: ${pid}\n**********\n";
            @posix_kill($pid, SIGTERM);
        }

        if ($dataDir and file_exists($dataDir)) {
            echo "\n**********\nClean up temporary data dir [${dataDir}] \n**********\n";
            @self::delTree($dataDir);
        }
    }

    public function testWithoutProxy()
    {
        /** @var HttpClientWrapper $client */
        $client = $this->getApp()->getContainer()->get('httpClient');

        $r = $client->get('https://www.baidu.com');

        $this->assertRegExp('/百度/', $r->getBody());
    }


    public function testGetEchoServer()
    {
        /** @var HttpClientWrapper $client */
        $client = $this->getApp()->getContainer()->get('httpClient');

        $r = $client->get('http://www.test.com:1024/testPath?testQuery');

        $response = $r->getBodyAsJson();

        $this->assertSame('http', $response['headers']['Scheme']);
        $this->assertSame('hello,world', $response['headers']['Yzc-Token']);
        $this->assertSame('www.test.com:1024', $response['headers']['Host']);
        $this->assertSame('GET', $response['server']['REQUEST_METHOD']);
        $this->assertSame(200, $r->getCode());;
    }

    public function testDeleteEchoServer()
    {
        /** @var HttpClientWrapper $client */
        $client = $this->getApp()->getContainer()->get('httpClient');

        $r = $client->delete('http://www.test.com:1024/testPath?testQuery');

        $response = $r->getBodyAsJson();

        $this->assertSame('http', $response['headers']['Scheme']);
        $this->assertSame('hello,world', $response['headers']['Yzc-Token']);
        $this->assertSame('www.test.com:1024', $response['headers']['Host']);
        $this->assertSame('DELETE', $response['server']['REQUEST_METHOD']);
        $this->assertSame(200, $r->getCode());;

    }

    public function testPostEchoServer()
    {
        /** @var HttpClientWrapper $client */
        $client = $this->getApp()->getContainer()->get('httpClient');

        $r = $client->post('http://www.test.com:2048/testPath?testQuery', ['Content-Type: application/json'], json_encode(['test' => 'json']));

        $response = $r->getBodyAsJson();

        $this->assertSame('http', $response['headers']['Scheme']);
        $this->assertSame('hello,world', $response['headers']['Yzc-Token']);
        $this->assertSame('www.test.com:2048', $response['headers']['Host']);
        $this->assertSame('POST', $response['server']['REQUEST_METHOD']);
        $this->assertSame(200, $r->getCode());

        $echoBody = json_decode($r->getBodyAsJson()['body'], true);
        $this->assertArrayHasKey('test', $echoBody);
        $this->assertSame('json', $echoBody['test']);
    }

    public function testPostMultipartEchoServer()
    {
        /** @var HttpClientWrapper $client */
        $client = $this->getApp()->getContainer()->get('httpClient');

        $r = $client->post('http://www.test.com:4096/testPath?testQuery', null, ['test' => 'multipart']);

        $response = $r->getBodyAsJson();

        $this->assertSame('http', $response['headers']['Scheme']);
        $this->assertSame('hello,world', $response['headers']['Yzc-Token']);
        $this->assertSame('www.test.com:4096', $response['headers']['Host']);
        $this->assertSame('POST', $response['server']['REQUEST_METHOD']);
        $this->assertSame(200, $r->getCode());

        $this->assertArrayHasKey('test', $r->getBodyAsJson()['body']);
        $this->assertSame('multipart', $r->getBodyAsJson()['body']['test']);

    }

    public function testPostUploadFileEchoServer()
    {
        /** @var HttpClientWrapper $client */
        $client = $this->getApp()->getContainer()->get('httpClient');


        $tempFilename = tempnam("/tmp", "temp_file_");
        file_put_contents($tempFilename, 'HelloWorld');
        $file = new CURLFile($tempFilename);

        $r = $client->post('http://www.test.com:10240/testPath?testQuery', null, ['test' => 'multipart', 'testFile' => $file]);
        unlink($tempFilename);

        $response = $r->getBodyAsJson();

        $this->assertSame('http', $response['headers']['Scheme']);
        $this->assertSame('hello,world', $response['headers']['Yzc-Token']);
        $this->assertSame('www.test.com:10240', $response['headers']['Host']);
        $this->assertSame('POST', $response['server']['REQUEST_METHOD']);
        $this->assertSame(200, $r->getCode());

        $this->assertArrayHasKey('test', $r->getBodyAsJson()['body']);
        $this->assertSame('multipart', $r->getBodyAsJson()['body']['test']);

        $this->assertArrayHasKey('files', $r->getBodyAsJson());
        $this->assertArrayHasKey('testFile', $r->getBodyAsJson()['files']);
        $this->assertSame(basename($tempFilename), $r->getBodyAsJson()['files']['testFile']['uploadFileName']);
        $this->assertSame('HelloWorld', $r->getBodyAsJson()['files']['testFile']['content']);

    }

    public function testPostFormUrlEncodedEchoServer()
    {
        /** @var HttpClientWrapper $client */
        $client = $this->getApp()->getContainer()->get('httpClient');

        $r = $client->post('http://www.test.com:1024/testPath?testQuery', null, http_build_query(['test' => 'urlencoded', 'test2' => 'param2']));

        $response = $r->getBodyAsJson();

        $this->assertSame('http', $response['headers']['Scheme']);
        $this->assertSame('hello,world', $response['headers']['Yzc-Token']);
        $this->assertSame('www.test.com:1024', $response['headers']['Host']);
        $this->assertSame('POST', $response['server']['REQUEST_METHOD']);
        $this->assertSame(200, $r->getCode());

        $this->assertArrayHasKey('test', $r->getBodyAsJson()['body']);
        $this->assertSame('urlencoded', $r->getBodyAsJson()['body']['test']);
        $this->assertSame('param2', $r->getBodyAsJson()['body']['test2']);

    }

    public function testPutEchoServer()
    {
        /** @var HttpClientWrapper $client */
        $client = $this->getApp()->getContainer()->get('httpClient');

        $r = $client->put('http://www.test.com:1024/testPath?testQuery', ['Content-Type: application/json'], json_encode(['test' => 'json']));

        $response = $r->getBodyAsJson();

        $this->assertSame('http', $response['headers']['Scheme']);
        $this->assertSame('hello,world', $response['headers']['Yzc-Token']);
        $this->assertSame('www.test.com:1024', $response['headers']['Host']);
        $this->assertSame('PUT', $response['server']['REQUEST_METHOD']);
        $this->assertSame(200, $r->getCode());

        $echoBody = json_decode($r->getBodyAsJson()['body'], true);
        $this->assertArrayHasKey('test', $echoBody);
        $this->assertSame('json', $echoBody['test']);

    }

}