<?php

namespace YouzanCloudBoot\Util;

use Symfony\Component\Yaml\Yaml;
use YouzanCloudBoot\Component\BaseComponent;
use YouzanCloudBoot\Constant\Env;

class EnvUtil extends BaseComponent
{

    private $apolloConfig = [];


    /**
     * 返回环境变量的值
     *
     * @param string $varName 环境变量名，区分大小写
     * @return null|string
     */
    public function get(string $varName): ?string
    {
        $key = str_replace('.', '_', $varName);
        if (isset($_SERVER[$key])) {
            return $_SERVER[$key];
        }

        return null;
    }

    private function getFromApollo(string $varName): ?string
    {
        if (empty($this->apolloConfig)) {
            $this->apolloConfig = Yaml::parseFile(Env::APOLLO_FILE);
        }

        return $this->apolloConfig[$varName] ?? null;
    }

    private function getFromEnv(string $varName): ?string
    {
        $key = str_replace('.', '_', $varName);
        if (isset($_SERVER[$key])) {
            return $_SERVER[$key];
        }

        return null;
    }

    public function getAppName(): ?string
    {
        $applicationName = $this->get('application.name');
        if (empty($applicationName)) {
            $applicationName = 'Youzan-Cloud-Boot-App';
        }
        return $applicationName;
    }

}