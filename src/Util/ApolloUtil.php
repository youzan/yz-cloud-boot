<?php


namespace YouzanCloudBoot\Util;


use Exception;
use YouzanCloudBoot\Component\BaseComponent;
use YouzanCloudBoot\Constant\Env;
use YouzanCloudBoot\Facades\LogFacade;
use YouzanCloudBoot\Helper\Arr;

class ApolloUtil extends BaseComponent
{

    // 用于缓存配置
    private static $config = null;

    // 合法的资源配置名称
    const VALID_APOLLO_RESOURCES = [
        'system', 'application'
    ];


    /**
     * 获取Apollo资源配置项
     *
     * @param string $resource system\application
     * @return array
     */
    public function get(string $resource): array
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
        // 若为空数组 表示统一资源配置即为空 也就是尚未添加资源组件
        // 若为null 表示尚未拉取统一资源配置 需要拉取配置
        $cacheValue = Arr::dot_get(self::$config, $resource);
        if (is_array($cacheValue)) {
            return $cacheValue;
        }

        try {
            /** @var \YouzanCloudBoot\Http\HttpClientResponse $resp */
            $resp = $this->getContainer()->get('httpClient')->get($this->buildHttpUrl($resource), $this->buildHttpHeaders());
            LogFacade::info('ApolloUtil http resp: ' . $resp->getBody());
            if (empty($resp->getBodyAsJson()) || !isset($resp->getBodyAsJson()['configurations'])) {
                return self::$config[$resource] = [];
            }

            return self::$config[$resource] = $resp->getBodyAsJson()['configurations'];
        } catch (Exception $e) {

        }

        return self::$config[$resource] = [];
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