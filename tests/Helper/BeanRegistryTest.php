<?php

namespace YouzanCloudBootTests\Helper;

use YouzanCloudBoot\Helper\BeanRegistry;
use YouzanCloudBootTests\Base\BaseTestCase;

class BeanRegistryTest extends BaseTestCase
{

    public function test() {
        /** @var BeanRegistry $registry */
        $registry = $this->getApp()->getContainer()->get('beanRegistry');
    }

}