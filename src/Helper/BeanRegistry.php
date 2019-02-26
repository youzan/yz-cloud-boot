<?php

namespace YouzanCloudBoot\Helper;

use ReflectionClass;

class BeanRegistry {

    private $beanPool = [];

    public function registerBean($beanName, $class, $tag = null) {
        if (class_exists($class, true)) {
            $ref = new ReflectionClass($class);
        } else {
            throw new BeanRegistryFailureException();
        }



    }

}