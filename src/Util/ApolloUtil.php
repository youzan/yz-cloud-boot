<?php

namespace YouzanCloudBoot\Util;

use Exception;
use Symfony\Component\Yaml\Yaml;
use YouzanCloudBoot\Component\BaseComponent;
use YouzanCloudBoot\Constant\Env;
use YouzanCloudBoot\Facades\LogFacade;

class ApolloUtil extends BaseComponent
{

    // 合法的资源配置名称
    const VALID_APOLLO_RESOURCES = [
        'system',
        'application'
    ];


    public function writeToFile($reties = 3)
    {
        if ($reties < 0) {
            LogFacade::warn("Apollo writeToFile. exceeds the maximum retries");
            return;
        }

        $configAll = array_merge($this->get('system'), $this->get('application'));
        if (empty($configAll)) {
            LogFacade::warn("Apollo writeToFile. the configAll empty");
            return $this->writeToFile(--$reties);
        }

        // write to file
        $res = file_put_contents(Env::getApolloFile(), Yaml::dump($configAll));
        if (false === $res) {
            LogFacade::warn("Apollo writeToFile. write return false");
            return $this->writeToFile(--$reties);
        }
    }


    /**
     * 获取Apollo资源配置项
     *
     * @param string $resource system\application
     * @return array
     */
    private function get(string $resource): array
    {
        if (!in_array($resource, self::VALID_APOLLO_RESOURCES)) {
            return [];
        }

        return $this->pull($resource);
    }


    /**
     * 拉取资源配置
     *
     * @param string $resource
     * @return array
     */
    private function pull(string $resource): array
    {
        try {
            /** @var \YouzanCloudBoot\Http\HttpClientResponse $resp */
            $resp = $this->getContainer()->get('httpClient')->get($this->buildHttpUrl($resource), $this->buildHttpHeaders());
            if (empty($resp->getBodyAsJson()) || !isset($resp->getBodyAsJson()['configurations'])) {
                return [];
            }

            return $resp->getBodyAsJson()['configurations'];
        } catch (Exception $e) {
            LogFacade::err('ApolloUtil pull, exception: ' . $e->getTraceAsString());
        }

        return [];
    }


    /**
     * 构造请求URL
     *
     * @param $resources string 资源名:system\application
     * @return string
     */
    private function buildHttpUrl($resources): string
    {
        $env = $this->getEnvUtil();

        return sprintf(
            "http://%s/configs/%s/default/%s", $env->get(Env::APOLLO_META_SERVER), $env->get(Env::APOLLO_APP_ID), $resources
        );
    }


    /**
     * 构造请求头
     *
     * @return array
     */
    private function buildHttpHeaders(): array
    {
        $env = $this->getEnvUtil();

        return [
            sprintf("auth: %s;%s", $env->get(Env::APOLLO_APP_ID), $env->get(Env::APOLLO_APP_SECRET))
        ];
    }


}