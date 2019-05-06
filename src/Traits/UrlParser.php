<?php
/**
 * Created by IntelliJ IDEA.
 * User: mac
 * Date: 2019/4/16
 * Time: 20:55
 */

namespace YouzanCloudBoot\Traits;

use YouzanCloudBoot\Exception\HttpClientException;

trait UrlParser
{

    protected function parseUrl($url)
    {
        $urlFragments = parse_url($url);
        if (!$urlFragments) {
            throw new HttpClientException('Unknown url');
        }

        $scheme = $host = $user = $pass = $port = $path = $query = '';

        if (!isset($urlFragments['scheme'])) {
            throw new HttpClientException('Unknown scheme');
        }
        if (strcasecmp($urlFragments['scheme'], 'http') !== 0
            and strcasecmp($urlFragments['scheme'], 'https') !== 0) {
            throw new HttpClientException('Only support http or https');
        }

        if (empty($urlFragments['host'])) {
            throw new HttpClientException('Unknown host');
        }

        $scheme = strtolower($urlFragments['scheme']);
        $host = $urlFragments['host'];

        if (!isset($urlFragments['port'])) {
            if (strcasecmp($scheme, 'http') === 0) {
                $port = 80;
            } else {
                if (strcasecmp($scheme, 'https') === 0) {
                    $port = 443;
                }
            }
        } else {
            $port = $urlFragments['port'];
        }

        if (!empty($urlFragments['user'])) {
            $user = $urlFragments['user'];
        }

        if (!empty($urlFragments['pass'])) {
            $pass = $urlFragments['pass'];
        }

        if (!empty($urlFragments['path'])) {
            $path = $urlFragments['path'];
        }

        if (!empty($urlFragments['query'])) {
            $query = $urlFragments['query'];
        }

        return [$scheme, $user, $pass, $host, $port, $path, $query];
    }
}