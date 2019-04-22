<?php

namespace YouzanCloudBoot\Http;

use Slim\Http\Response;
use YouzanCloudBoot\Traits\UrlParser;

class HttpClientWrapper
{

    use UrlParser;

    public $responseBody;
    protected $body = null;
    protected $username = null;
    protected $password = null;
    protected $acceptType;
    protected $responseInfo;

    private $proxy;
    private $token;
    private $ignoreList = [];

    private $url;
    private $method = 'GET';
    private $curlHandle;

    /**
     * HttpClientWrapper constructor.
     * 不建议直接代码访问此构造函数 (即 new YouzanCloudBoot\Http\HttpClientWrapper(...))
     * 应该使用 HttpClientFactory 进行实例化
     * 即 $this->getContainer()->get('httpClientFactory')->buildHttpClient()
     * @see HttpClientFactory
     *
     * @param string $proxy
     * @param string $token
     * @param array $ignoreList
     */
    public function __construct(string $proxy = null, string $token = null, array $ignoreList = [])
    {
        $this->proxy = $proxy;
        $this->curlHandle = curl_init();
        $this->token = $token;

        if (!empty($ignoreList)) {
            $this->ignoreList = $ignoreList;
        }
    }

    public function get($requestUrl, array $headers = null)
    {
        list($scheme, $user, $pass, $host, $port, $path, $query) = $this->parseUrl($requestUrl);

        if (in_array($host, $this->ignoreList) or empty($this->proxy)) {
            $realRequestUrl = $requestUrl;
            return $this->doRequest(CURLOPT_HTTPGET, $realRequestUrl, false, $scheme, $headers, null);
        }

        $realRequestUrl = 'http://' . $this->proxy . ($path ? $path . ($query ? '?' . $query : '') : '');
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


        return $this->doRequest(CURLOPT_HTTPGET, $realRequestUrl, true, $scheme, $headers, null);
    }

    public function post($url, array $headers = null, $body = null)
    {
        return $this->doRequest(CURLOPT_POST, $url, $headers, $body);
    }

    public function put($url, array $headers = null, $body = null)
    {

    }

    public function delete($url, array $headers = null)
    {

    }

    public function doRequest($method, $url, $withProxy, $scheme, array $headers = null, $body = null)
    {
        curl_setopt($this->curlHandle, $method, true);

        if (!$withProxy and $scheme === 'https') {
            // FIXME 跳过了服务器校验，降低了安全性（可以被中间人攻击）
            curl_setopt($this->curlHandle, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($this->curlHandle, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($this->curlHandle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($this->curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curlHandle, CURLOPT_URL, $url);
        curl_setopt($this->curlHandle, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->curlHandle, CURLOPT_MAXREDIRS, 10);

        //debug观察输出的话取消下行注释
        //curl_setopt($this->curlHandle, CURLOPT_VERBOSE, true);

        if (!empty($body)) {

        }


        $response = new Response();

        curl_setopt($this->curlHandle, CURLOPT_HEADERFUNCTION, function ($curl, $headerLine) use ($response) {
            if (strncasecmp($headerLine, 'HTTP', 4) == 0) {

            }
//            list($key, $value) = explode(':', $headerLine);
//            $headerStream->add($key, $value);

            return strlen($headerLine);
        });

        return curl_exec($this->curlHandle);
    }

//    public function clean()
//    {
//        $this->body = null;
//        $this->method = 'POST';
//        $this->responseBody = null;
//        $this->responseInfo = null;
//    }
//
//    public function execute()
//    {
//        $ch = curl_init();
//        $this->setAuth($ch);
//        try {
//            switch (strtoupper($this->method)) {
//                case 'GET' :
//                    $this->executeGet($ch);
//                    break;
//                case 'POST' :
//                    $this->executePost($ch);
//                    break;
//                default :
//                    ;
//            }
//        } catch (Exception $e) {
//            curl_close($ch);
//            throw $e;
//        }
//    }
//
//    protected function setAuth($curlHandle)
//    {
//        if ($this->username !== null && $this->password !== null) {
//            curl_setopt($curlHandle, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);//HTTP验证方法
//            curl_setopt($curlHandle, CURLOPT_USERPWD, $this->username . ':' . $this->password);
//        }
//    }
//
//    protected function executeGet($ch)
//    {
//        $this->doExecute($ch);
//    }
//
//    protected function doExecute($curlHandle)
//    {
//        $this->setCurlOpts($curlHandle);
//        $this->responseBody = curl_exec($curlHandle);
//        $this->responseInfo = curl_getinfo($curlHandle);
//
//        curl_close($curlHandle);
//    }
//
//    protected function setCurlOpts($curlHandle)
//    {
//        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 20);
//        curl_setopt($curlHandle, CURLOPT_URL, $this->url);
//        //不将结果直接输出
//        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $this->acceptType);
//    }
//
//    protected function executePost($ch)
//    {
//        if (!is_string($this->body)) {
//            $this->buildPostBody();
//        }
//
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->body);
//        curl_setopt($ch, CURLOPT_POST, 1);
//
//        $this->doExecute($ch);
//    }
//
//    public function buildPostBody($data = null)
//    {
//        $data = ($data !== null) ? $data : $this->body;
//        if (!is_array($data)) {
//        }
//
//        $data = http_build_query($data, '', '&');
//
//        $this->body = $data;
//    }

}