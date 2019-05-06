<?php

namespace YouzanCloudBoot\Http;

use Monolog\Logger;
use YouzanCloudBoot\Exception\HttpClientException;
use YouzanCloudBoot\Traits\UrlParser;

class HttpClientWrapper
{

    use UrlParser;

    private $proxy;
    private $token;
    private $ignoreList = [];

    private $curlHandle;

    private $logger;

    /**
     * HttpClientWrapper constructor.
     * 不建议直接代码访问此构造函数 (即 new YouzanCloudBoot\Http\HttpClientWrapper(...))
     * 应该使用 HttpClientFactory 进行实例化
     * 即 $this->getContainer()->get('httpClientFactory')->buildHttpClient()
     * @param string $proxy
     * @param string $token
     * @param array $ignoreList
     * @param Logger|null $logger
     * @see HttpClientFactory
     *
     */
    public function __construct(string $proxy = null, string $token = null, array $ignoreList = [], Logger $logger = null)
    {
        $this->proxy = $proxy;
        $this->curlHandle = curl_init();
        $this->token = $token;
        $this->logger = $logger;

        if (!empty($ignoreList)) {
            $this->ignoreList = $ignoreList;
        }
    }

    /**
     * 发起一个 Get 请求并获得返回
     *
     * @param string $url
     * @param array|null $headers
     * @return WrappedResponse
     * @throws HttpClientException
     */
    public function get(string $url, array $headers = null) : WrappedResponse
    {
        list($scheme, $user, $pass, $host, $port, $path, $query) = $this->parseUrl($url);

        if (in_array($host, $this->ignoreList) or empty($this->proxy)) {
            $this->logger->info(sprintf("Get directly, url: %s, headers: %s", $url, json_encode($headers)));
            return $this->doRequest('GET', $url, false, $scheme, $headers, null);
        }

        $realRequestUrl = $this->parseRealRequestUrl($path, $query);
        $realRequestHeaders = $this->parseHeaders($headers, $host, $user, $pass, $port, $scheme);
        $this->logger->info(sprintf("Get through proxy, url: %s, headers: %s", $realRequestUrl, json_encode($realRequestHeaders)));

        return $this->doRequest('GET', $realRequestUrl, true, $scheme, $realRequestHeaders, null);
    }

    /**
     * 发起一个 Post 请求并获得返回
     *
     * @param string $url
     * @param array|null $headers
     * @param null $body
     * @return WrappedResponse
     * @throws HttpClientException
     */
    public function post(string $url, array $headers = null, $body = null) : WrappedResponse
    {
        list($scheme, $user, $pass, $host, $port, $path, $query) = $this->parseUrl($url);

        if (in_array($host, $this->ignoreList) or empty($this->proxy)) {
            $this->logger->info(sprintf("Post directly, url: %s, headers: %s", $url, json_encode($headers)));
            return $this->doRequest('POST', $url, false, $scheme, $headers, $body);
        }

        $realRequestUrl = $this->parseRealRequestUrl($path, $query);
        $realRequestHeaders = $this->parseHeaders($headers, $host, $user, $pass, $port, $scheme);
        $this->logger->info(sprintf("Post through proxy, url: %s, headers: %s", $realRequestUrl, json_encode($realRequestHeaders)));

        return $this->doRequest('POST', $realRequestUrl, true, $scheme, $realRequestHeaders, $body);
    }

    /**
     * 发起一个 Put 请求并获得返回
     *
     * @param $url
     * @param array|null $headers
     * @param null $body
     * @return WrappedResponse
     * @throws HttpClientException
     */
    public function put($url, array $headers = null, $body = null) : WrappedResponse
    {
        list($scheme, $user, $pass, $host, $port, $path, $query) = $this->parseUrl($url);

        if (in_array($host, $this->ignoreList) or empty($this->proxy)) {
            $this->logger->info(sprintf("Put directly, url: %s, headers: %s", $url, json_encode($headers)));
            return $this->doRequest('PUT', $url, false, $scheme, $headers, $body);
        }

        $realRequestUrl = $this->parseRealRequestUrl($path, $query);
        $realRequestHeaders = $this->parseHeaders($headers, $host, $user, $pass, $port, $scheme);
        $this->logger->info(sprintf("Put through proxy, url: %s, headers: %s", $realRequestUrl, json_encode($realRequestHeaders)));

        return $this->doRequest('PUT', $realRequestUrl, true, $scheme, $realRequestHeaders, $body);
    }

    /**
     * 发起一个 Delete 请求并获得返回
     *
     * @param $url
     * @param array|null $headers
     * @return WrappedResponse
     * @throws HttpClientException
     */
    public function delete($url, array $headers = null) : WrappedResponse
    {
        list($scheme, $user, $pass, $host, $port, $path, $query) = $this->parseUrl($url);

        if (in_array($host, $this->ignoreList) or empty($this->proxy)) {
            $this->logger->info(sprintf("Delete directly, url: %s, headers: %s", $url, json_encode($headers)));
            return $this->doRequest('DELETE', $url, false, $scheme, $headers, null);
        }

        $realRequestUrl = $this->parseRealRequestUrl($path, $query);
        $realRequestHeaders = $this->parseHeaders($headers, $host, $user, $pass, $port, $scheme);
        $this->logger->info(sprintf("Delete through proxy, url: %s, headers: %s", $realRequestUrl, json_encode($realRequestHeaders)));

        return $this->doRequest('DELETE', $realRequestUrl, true, $scheme, $realRequestHeaders, null);
    }

    /**
     * 对外请求的统一封装
     *
     * @param $method
     * @param $url
     * @param $withProxy
     * @param $scheme
     * @param array|null $headers
     * @param null $body
     * @return WrappedResponse
     */
    protected function doRequest($method, $url, $withProxy, $scheme, array $headers = null, $body = null)
    {
        curl_setopt($this->curlHandle, CURLOPT_CUSTOMREQUEST, $method);

        if (!$withProxy and $scheme === 'https') {
            // FIXME 跳过了服务器校验，降低了安全性（可以被中间人攻击）
            curl_setopt($this->curlHandle, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($this->curlHandle, CURLOPT_SSL_VERIFYHOST, false);
        }
        if ($headers) {
            curl_setopt($this->curlHandle, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($this->curlHandle, CURLOPT_HEADER, true);
        curl_setopt($this->curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curlHandle, CURLOPT_URL, $url);
        curl_setopt($this->curlHandle, CURLOPT_FOLLOWLOCATION, true);
        // 设置一个最大允许重定向的次数防止溢出
        curl_setopt($this->curlHandle, CURLOPT_MAXREDIRS, 10);

        // 消息体
        if (!empty($body)) {
            curl_setopt($this->curlHandle, CURLOPT_POSTFIELDS, $body);
        }

        // Ddebug观察输出的话取消下行注释
        // curl_setopt($this->curlHandle, CURLOPT_VERBOSE, true);

        $response = curl_exec($this->curlHandle);

        $responseHeaderSize = curl_getinfo($this->curlHandle, CURLINFO_HEADER_SIZE);
        $responseCode = curl_getinfo($this->curlHandle, CURLINFO_HTTP_CODE);
        $responseHeaders = substr($response, 0, $responseHeaderSize);
        $responseBody = substr($response, $responseHeaderSize);

        curl_reset($this->curlHandle);

        return new WrappedResponse($responseCode, $responseHeaders, $responseBody);
    }

    /**
     * @param $path
     * @param $query
     * @return string
     */
    private function parseRealRequestUrl($path, $query): string
    {
        $realRequestUrl = 'http://' . $this->proxy . ($path ? $path . ($query ? '?' . $query : '') : '');
        return $realRequestUrl;
    }

    /**
     * @param $headers
     * @param $host
     * @param $user
     * @param $pass
     * @param $port
     * @param $scheme
     * @return array
     */
    private function parseHeaders($headers, $host, $user, $pass, $port, $scheme): array
    {
        if (!is_array($headers)) {
            $headers = [];
        }
        $requestHost = $host;
        if ($user) {
            if ($pass) {
                $requestHost = $user . ':' . $pass . '@' . $host;
            } else {
                $requestHost = $user . '@' . $host;
            }
        }
        $headers[] = 'Host: ' . $requestHost;
        $headers[] = 'Port: ' . $port;
        $headers[] = 'Scheme: ' . $scheme;
        $headers[] = 'Yzc-Token: ' . $this->token;
        return $headers;
    }

}