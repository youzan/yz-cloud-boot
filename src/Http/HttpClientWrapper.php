<?php

namespace YouzanCloudBoot\Http;

use Psr\Container\ContainerInterface;
use YouzanCloudBoot\Component\BaseComponent;
use YouzanCloudBoot\Exception\HttpClientException;
use YouzanCloudBoot\Traits\UrlParser;

class HttpClientWrapper extends BaseComponent
{

    use UrlParser;

    private $proxy;
    private $token;
    private $ignoreList = [];

    private $curlHandle;

    private $innerServiceHost = '.s.youzanyun.net';

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $env = $this->getEnvUtil();

        $enable = $env->get('youzan.proxy.enable');

        if ($enable !== 'true') {
            $proxy = '';
        } else {
            $proxy = $env->get('youzan.proxy.host');
        }

        $token = $env->get('youzan.proxy.token');

        $nonProxyHosts = $env->get('youzan.proxy.nonProxyHosts');
        if ($nonProxyHosts) {
            $ignoreList = explode(',', $nonProxyHosts);
        } else {
            $ignoreList = [];
        }

        $this->init($proxy, $token, $ignoreList);
    }

    /**
     * 初始化 Client
     *
     * @param string $proxy
     * @param string $token
     * @param array $ignoreList
     * @see HttpClientFactory
     *
     */
    private function init(string $proxy = null, string $token = null, array $ignoreList = [])
    {
        $this->proxy = $proxy;
        $this->curlHandle = curl_init();
        $this->token = $token;

        if (!empty($ignoreList)) {
            $this->ignoreList = $ignoreList;
        }
    }

    /**
     * 发起一个 Get 请求并获得返回
     *
     * @param string $url
     * @param array|null $headers
     * @return HttpClientResponse
     * @throws HttpClientException
     */
    public function get(string $url, array $headers = null): HttpClientResponse
    {
        list($scheme, $user, $pass, $host, $port, $path, $query) = $this->parseUrl($url);

        if (empty($this->proxy) or $this->isInnerService($host)) {
            if ($this->isDebug()) {
                $this->getLog()->info(sprintf("Get directly, url: %s, headers: %s", $url, json_encode($headers)));
            }
            return $this->doRequest('GET', $url, false, $scheme, $headers, null);
        }

        $realRequestUrl = $this->parseRealRequestUrl($path, $query);
        $realRequestHeaders = $this->parseHeaders($headers, $host, $user, $pass, $port, $scheme);
        if ($this->isDebug()) {
            $this->getLog()->info(sprintf("Get through proxy, url: %s, headers: %s", $realRequestUrl, json_encode($realRequestHeaders)));
        }

        return $this->doRequest('GET', $realRequestUrl, true, $scheme, $realRequestHeaders, null);
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
     * @return HttpClientResponse
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

        return new HttpClientResponse($responseCode, $responseHeaders, $responseBody);
    }

    /**
     * @param $path
     * @param $query
     * @return string
     */
    private function parseRealRequestUrl($path, $query): string
    {
        $realRequestUrl = $this->proxy . ($path ? $path . ($query ? '?' . $query : '') : '');
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
        $headers[] = 'Host: ' . $requestHost . (in_array($port, ["80", "443"]) ? '' : ':' . $port);
        $headers[] = 'Scheme: ' . $scheme;
        $headers[] = 'Yzc-Token: ' . $this->token;
        return $headers;
    }

    /**
     * 发起一个 Post 请求并获得返回
     *
     * @param string $url
     * @param array|null $headers
     * @param null $body
     * @return HttpClientResponse
     * @throws HttpClientException
     */
    public function post(string $url, array $headers = null, $body = null): HttpClientResponse
    {
        list($scheme, $user, $pass, $host, $port, $path, $query) = $this->parseUrl($url);

        if (empty($this->proxy) or $this->isInnerService($host)) {
            if ($this->isDebug()) {
                $this->getLog()->info(sprintf("Post directly, url: %s, headers: %s", $url, json_encode($headers)));
            }
            return $this->doRequest('POST', $url, false, $scheme, $headers, $body);
        }

        $realRequestUrl = $this->parseRealRequestUrl($path, $query);
        $realRequestHeaders = $this->parseHeaders($headers, $host, $user, $pass, $port, $scheme);
        if ($this->isDebug()) {
            $this->getLog()->info(sprintf("Post through proxy, url: %s, headers: %s", $realRequestUrl, json_encode($realRequestHeaders)));
        }

        return $this->doRequest('POST', $realRequestUrl, true, $scheme, $realRequestHeaders, $body);
    }

    /**
     * 发起一个 Put 请求并获得返回
     *
     * @param $url
     * @param array|null $headers
     * @param null $body
     * @return HttpClientResponse
     * @throws HttpClientException
     */
    public function put($url, array $headers = null, $body = null): HttpClientResponse
    {
        list($scheme, $user, $pass, $host, $port, $path, $query) = $this->parseUrl($url);

        if (empty($this->proxy) or $this->isInnerService($host)) {
            if ($this->isDebug()) {
                $this->getLog()->info(sprintf("Put directly, url: %s, headers: %s", $url, json_encode($headers)));
            }
            return $this->doRequest('PUT', $url, false, $scheme, $headers, $body);
        }

        $realRequestUrl = $this->parseRealRequestUrl($path, $query);
        $realRequestHeaders = $this->parseHeaders($headers, $host, $user, $pass, $port, $scheme);
        if ($this->isDebug()) {
            $this->getLog()->info(sprintf("Put through proxy, url: %s, headers: %s", $realRequestUrl, json_encode($realRequestHeaders)));
        }

        return $this->doRequest('PUT', $realRequestUrl, true, $scheme, $realRequestHeaders, $body);
    }

    /**
     * 发起一个 Delete 请求并获得返回
     *
     * @param $url
     * @param array|null $headers
     * @return HttpClientResponse
     * @throws HttpClientException
     */
    public function delete($url, array $headers = null): HttpClientResponse
    {
        list($scheme, $user, $pass, $host, $port, $path, $query) = $this->parseUrl($url);

        if (empty($this->proxy) or $this->isInnerService($host)) {
            if ($this->isDebug()) {
                $this->getLog()->info(sprintf("Delete directly, url: %s, headers: %s", $url, json_encode($headers)));
            }
            return $this->doRequest('DELETE', $url, false, $scheme, $headers, null);
        }

        $realRequestUrl = $this->parseRealRequestUrl($path, $query);
        $realRequestHeaders = $this->parseHeaders($headers, $host, $user, $pass, $port, $scheme);
        if ($this->isDebug()) {
            $this->getLog()->info(sprintf("Delete through proxy, url: %s, headers: %s", $realRequestUrl, json_encode($realRequestHeaders)));
        }

        return $this->doRequest('DELETE', $realRequestUrl, true, $scheme, $realRequestHeaders, null);
    }

    private function isInnerService($host)
    {
        return in_array($host, $this->ignoreList) or (substr($host, -strlen($this->innerServiceHost)) === $this->innerServiceHost);
    }

}