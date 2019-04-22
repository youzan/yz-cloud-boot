<?php

namespace YouzanCloudBoot\Http;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Slim\Http\Request;
use Slim\Interfaces\Http\HeadersInterface;

class WrappedHttpRequest extends Request
{

    public function __construct(string $method, UriInterface $uri, HeadersInterface $headers, array $cookies, array $serverParams, StreamInterface $body, array $uploadedFiles = [])
    {
        parent::__construct($method, $uri, $headers, $cookies, $serverParams, $body, $uploadedFiles);
    }


    public function exec()
    {

    }

}