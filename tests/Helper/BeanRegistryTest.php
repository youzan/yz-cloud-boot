<?php

namespace YouzanCloudBootTests\Helper;

use YouzanCloudBoot\Helper\BeanRegistry;
use YouzanCloudBootTests\Base\BaseTestCase;

class BeanRegistryTest extends BaseTestCase
{

    public function testRegister() {
        /** @var BeanRegistry $registry */
        $registry = $this->getApp()->getContainer()->get('beanRegistry');
    }

}