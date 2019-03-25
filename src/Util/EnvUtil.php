<?php

namespace YouzanCloudBoot\Util;

use YouzanCloudBoot\Component\BaseComponent;

class EnvUtil extends BaseComponent
{

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

}