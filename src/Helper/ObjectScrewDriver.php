<?php

namespace YouzanCloudBoot\Helper;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;
use YouzanCloudBoot\Exception\CommonException;
use YouzanCloudBoot\Exception\ExtensionPointHandleException;
use YouzanCloudBoot\Helper\Traits\ClassValidator;

class ObjectScrewDriver
{

    use ClassValidator;

    /**
     * 转换 incoming 为目标方法的唯一参数
     *
     * @param $incoming
     * @param ReflectionMethod $method
     * @return mixed|null
     * @throws ExtensionPointHandleException
     * @throws ReflectionException
     * @throws CommonException
     */
    public function convertObjectToMethodExclusiveParam($incoming, ReflectionMethod $method)
    {
        if (count($method->getParameters()) > 1) {
            // 严格限定参数只有0个或者1个
            throw new ExtensionPointHandleException('The number of parameters cannot exceed 1');
        }

        if (count($method->getParameters()) == 0) {
            return null;
        }

        /** @var ReflectionParameter $parameter */
        $parameter = current($method->getParameters());
        $type = $parameter->getType();

        switch ($type->getName()) {
            case 'string':
            case 'int':
            case 'bool':
            case 'float':
            case 'array':
                return $incoming;
            case 'callable':
            case 'iterable':
            case 'object':
                throw new ExtensionPointHandleException('Unsupported parameter type');
        }

        $this->assertClassExists($type->getName(), true);

        $refParameterClass = new ReflectionClass($type->getName());
        $instance = $refParameterClass->newInstanceWithoutConstructor();

        foreach ($incoming as $k => $v) {
            $methodName = 'set' . ucfirst($k);
            if (!$refParameterClass->hasMethod($methodName)) {
                // 没有setter方法则跳过此属性
                continue;
            }
            $setter = $refParameterClass->getMethod($methodName);
            if (is_object($v)) {
                $setter->invoke($instance, $this->convertObjectToMethodExclusiveParam($v, $setter));
            } else {
                $setter->invoke($instance, $v);
            }
        }

        return $instance;
    }

}