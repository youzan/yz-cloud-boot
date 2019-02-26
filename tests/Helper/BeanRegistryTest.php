<?php

namespace YouzanCloudBootTests\Helper;

use YouzanCloudBoot\Bep\BeanRegistry;
use YouzanCloudBootTests\Base\BaseTestCase;

class BeanRegistryTest extends BaseTestCase
{

    public function testRegister() {
        /** @var \YouzanCloudBoot\Bep\BeanRegistry $registry */
        $registry = $this->getApp()->getContainer()->get('beanRegistry');
    }

}