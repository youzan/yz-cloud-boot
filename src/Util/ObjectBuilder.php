<?php

namespace YouzanCloudBoot\Util;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;
use YouzanCloudBoot\Component\BaseComponent;
use YouzanCloudBoot\Exception\CommonException;
use YouzanCloudBoot\Exception\ExtensionPointHandleException;
use YouzanCloudBoot\Traits\ExtensionPointUtil;

class ObjectBuilder extends BaseComponent
{

    use ExtensionPointUtil;

    /**
     * 转换 input 为目标方法的唯一参数
     *
     * 这个方法的整体思路是，反射取出目标方法的唯一参数
     * 根据输入对唯一参数的每个属性，逐个调用 setter 方法进行逐一赋值，如果某个 setter 方法的参数是一个对象，则递归调用此方法进行设置
     *
     * @param ReflectionMethod $method
     * @param array $input JSON 数据，用关联数组存储
     * @return mixed|null
     * @throws CommonException
     * @throws ExtensionPointHandleException
     * @throws ReflectionException
     */
    public function convertArrayToMethodExclusiveParam(ReflectionMethod $method, $input)
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
                return $input;
            case 'callable':
            case 'iterable':
            case 'object':
                throw new ExtensionPointHandleException('Unsupported parameter type');
        }

        $this->assertClassExists($type->getName(), true);
        $refParameterClass = new ReflectionClass($type->getName());

        return $this->convertArrayToObjectInstance($input, $refParameterClass);
    }

    /**
     * 把 PHP 关联数组转换成目标类的实例
     *
     * @param mixed $input
     * @param ReflectionClass $refParameterClass
     * @return mixed
     * @throws CommonException
     * @throws ExtensionPointHandleException
     * @throws ReflectionException
     */
    public function convertArrayToObjectInstance($input, ReflectionClass $refParameterClass)
    {
        if (is_null($input)) {
            return $input;
        }

        // 匿名类直接转换
        if ($refParameterClass->getName() == 'stdClass') {
            return json_decode(json_encode($input));
        }

        $instance = $refParameterClass->newInstanceWithoutConstructor();

        foreach ($input as $propertyName => $propertyValue) {
            $methodName = 'set' . ucfirst($propertyName);
            if (!$refParameterClass->hasMethod($methodName)) {
                // 没有setter方法则跳过此属性
                continue;
            }
            $setter = $refParameterClass->getMethod($methodName);
            if (is_array($propertyValue)) {
                $matches = [];
                preg_match('/@param ([A-Za-z0-9_\\\\]+)((?:\[\])+)/', $setter->getDocComment(), $matches);

                if (is_scalar($propertyValue) || empty($matches)) {
                    $setter->invoke($instance, $this->convertArrayToMethodExclusiveParam($setter, $propertyValue));
                } else {
                    $listLevelsCount = substr_count(end($matches), '[]');
                    $memberType = $this->fillUpNamespaceWithType($matches[1], $refParameterClass->getNamespaceName());

                    $setter->invoke($instance, $this->diveIntoMatrix($propertyValue, $memberType, $listLevelsCount));
                }
            } else {
                $setter->invoke($instance, $propertyValue);
            }
        }

        return $instance;
    }

    private function fillUpNamespaceWithType($typeName, $refClassNamespace)
    {
        if ($this->isPrimitiveType($typeName) || $typeName == 'stdClass') {
            return $typeName;
        } else {
            /**
             * 这个判断可能有点过于粗暴, 但是暂时也没有想到更好的办法
             */
            if (strpos($typeName, '\\') !== false) {
                return $typeName;
            } else {
                return $refClassNamespace . '\\' . $typeName;
            }
        }
    }

    /**
     *
     * @see http://php.net/manual/en/function.gettype.php reference
     * @param string $type
     * @return bool
     */
    private function isPrimitiveType(string $type): bool
    {
        switch ($type) {
            case 'string':
            case 'integer':
            case 'double':
            case 'boolean':
            case 'NULL':
            case 'int':
            case 'number':
            case 'bool':
            case 'float':
                return true;
        }
        return false;
    }

    private function diveIntoMatrix($propertyValue, $memberType, $levels)
    {
        if ($this->isPrimitiveType($memberType)) {
            return $propertyValue;
        }
        if ($levels > 1) {
            $r = [];
            foreach ($propertyValue as $item) {
                $r[] = $this->diveIntoMatrix($item, $memberType, $levels - 1);
            }
            return $r;
        }

        $ref = new ReflectionClass($memberType);
        $instanceValues = array_map(function ($item) use ($ref) {
            return $this->convertArrayToObjectInstance($item, $ref);
        }, $propertyValue);

        return $instanceValues;
    }

}