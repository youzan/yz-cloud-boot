<?php

namespace YouzanCloudBoot\Store;

use Redis;
use YouzanCloudBoot\Component\BaseComponent;

class RedisFactory extends BaseComponent
{

    public function buildRedisInstance($host, $port): Redis
    {
        $redis = new Redis();
        $redis->connect($host, $port);
        return $redis;
    }

    public function buildBuiltinRedisInstance(): ?Redis
    {
        $host = $this->getEnvUtil()->getFromApollo('redis.host');
        $port = $this->getEnvUtil()->getFromApollo('redis.port');

        if (empty($host)) {
            return null;
        }

        return $this->buildRedisInstance($host, $port);
    }

}