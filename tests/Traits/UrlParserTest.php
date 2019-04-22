<?php

namespace YouzanCloudBootTests\Traits;

use YouzanCloudBoot\Exception\HttpClientException;
use YouzanCloudBoot\Traits\UrlParser;
use YouzanCloudBootTests\Base\BaseTestCase;

class UrlParserTest extends BaseTestCase
{
    use UrlParser;

    public function dataProvider()
    {
        return [
            [
                'http://a:b@www.test.com:1234/hello?a=b&c=d#a',
                'http',
                'a',
                'b',
                'www.test.com',
                1234,
                'hello',
                'a=b&c=d'
            ],
            [
                'https://a@www.test.com/',
                'https',
                'a',
                '',
                'www.test.com',
                443,
                '',
                ''
            ],
            [
                'http://test/a.html',
                'http',
                '',
                '',
                'test',
                80,
                'a.html',
                ''
            ]
        ];
    }


    /**
     * @dataProvider dataProvider
     * @param $url
     * @param $expectScheme
     * @param $expectHost
     * @param $expectPort
     * @param $expectPath
     * @param $expectQuery
     * @throws \YouzanCloudBoot\Exception\HttpClientException
     */
    public function testNormal($url, $expectScheme, $expectUser, $expectPass, $expectHost, $expectPort, $expectPath, $expectQuery)
    {
        list($scheme, $user, $pass, $host, $port, $path, $query) = $this->parseUrl($url);

        $this->assertSame($expectScheme, $scheme);
        $this->assertSame($expectUser, $user);
        $this->assertSame($expectPass, $pass);
        $this->assertSame($host, $host);
        $this->assertSame($port, $port);
        $this->assertSame($path, $path);
        $this->assertSame($query, $query);
    }


    public function exceptionDataProvider()
    {
        return [
            [
                'ftp://www.test.com/a',
                HttpClientException::class,
                'Only support http or https'
            ],
            [
                'mailto:hello@world',
                HttpClientException::class,
                'Only support http or https',
            ],
            [
                'test/a.html',
                HttpClientException::class,
                'Unknown scheme',
            ],
            [
                'file:///test/a.html',
                HttpClientException::class,
                'Only support http or https',
            ],
            [
                'http:///a.html',
                HttpClientException::class,
                'Unknown url',
            ]
        ];
    }


    /**
     * @dataProvider exceptionDataProvider
     * @param $url
     * @param $expectExceptionClass
     * @param $expectExceptionMessage
     * @throws HttpClientException
     */
    public function testException($url, $expectExceptionClass, $expectExceptionMessage)
    {
        $this->expectException($expectExceptionClass);
        $this->expectExceptionMessage($expectExceptionMessage);
        $r = $this->parseUrl($url);
    }

}