<?php

namespace YouzanCloudBoot\Traits;

use YouzanCloudBoot\Exception\CommonException;

trait ClassValidator
{

    /**
     * 断言类是否存在
     *
     * @param string $className
     * @param bool $autoload
     * @return bool
     * @throws CommonException
     */
    private function assertClassExists(string $className, bool $autoload): bool
    {
        if (!class_exists($className, $autoload)) {
            throw new CommonException('Class [' . $className . '] not exists');
        }
        return true;
    }

    /**
     * 断言接口是否存在
     *
     * @param string $interfaceName
     * @param bool $autoload
     * @return bool
     * @throws CommonException
     */
    private function assertInterfaceExists(string $interfaceName, bool $autoload): bool
    {
        if (!interface_exists($interfaceName, $autoload)) {
            throw new CommonException('Interface [' . $interfaceName . '] not exists');
        }
        return true;
    }
}