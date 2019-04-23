<?php

namespace YouzanCloudBoot\Traits;

use ReflectionClass;
use YouzanCloudBoot\Exception\CommonException;
use YouzanCloudBoot\Exception\ExtensionPointHandleException;

trait ExtensionPointUtil
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

    /**
     * 将 Java 风格的类名转换为 PHP UpperCamelCase 风格
     *
     * @param $serviceName
     * @return string
     * @throws ExtensionPointHandleException
     */
    protected function parseServiceInterfaceName($serviceName)
    {
        $serviceNameParts = explode(".", $serviceName);
        $serviceNamePartsInUpperCamelCase = array_map('ucfirst', $serviceNameParts);
        $serviceInterfaceName = implode('\\', $serviceNamePartsInUpperCamelCase);

        if (empty($serviceInterfaceName)) {
            throw new ExtensionPointHandleException('Error request [interface name error]');
        }

        return $serviceInterfaceName;
    }

    /**
     * 断言该实现类是否实现了对应的接口
     * @param $ref
     * @param $serviceInterfaceName
     * @return bool
     * @throws ExtensionPointHandleException
     */
    protected function assertInterfaceImplemented(ReflectionClass $ref, $serviceInterfaceName): bool
    {
        $interfaces = $ref->getInterfaces();

        foreach ($interfaces as $interface) {
            if ($interface->getName() == $serviceInterfaceName) {
                return true;
            }
        }

        throw new ExtensionPointHandleException(
            'Interface [' . $serviceInterfaceName . '] not implemented in class [' . $ref->getName() . ']'
        );
    }
}