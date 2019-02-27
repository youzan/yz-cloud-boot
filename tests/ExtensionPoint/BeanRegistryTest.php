<?php

namespace YouzanCloudBootTests\ExtensionPoint;

use YouzanCloudBoot\ExtensionPoint\BeanRegistry;
use YouzanCloudBootTests\Base\BaseTestCase;
use YouzanCloudBootTests\Stub\FakeBean;

class BeanRegistryTest extends BaseTestCase
{

    public function testRegisterNormal() {
        /** @var \YouzanCloudBoot\ExtensionPoint\BeanRegistry $registry */
        $registry = $this->getApp()->getContainer()->get('beanRegistry');
        
        $registry->registerBean('fakeBean', FakeBean::class);
        $registry->registerBean('fakeBean', FakeBean::class, '1.0');
        $registry->registerBean('fakeBean', FakeBean::class, '2.0');
        $this->assertTrue(true);
    }
    
    public function testRegisterDuplicated() {
        /** @var \YouzanCloudBoot\ExtensionPoint\BeanRegistry $registry */
        $registry = $this->getApp()->getContainer()->get('beanRegistry');

        $this->expectExceptionMessage('registered');
        $registry->registerBean('fakeBean', FakeBean::class);
        $registry->registerBean('fakeBean', FakeBean::class);
    }

    public function testRegisterDuplicatedWithTag() {
        /** @var \YouzanCloudBoot\ExtensionPoint\BeanRegistry $registry */
        $registry = $this->getApp()->getContainer()->get('beanRegistry');

        $this->expectExceptionMessage('registered');
        $registry->registerBean('fakeBean', FakeBean::class, '1.0');
        $registry->registerBean('fakeBean', FakeBean::class, '1.0');
    }

    public function testGet() {
        /** @var \YouzanCloudBoot\ExtensionPoint\BeanRegistry $registry */
        $registry = $this->getApp()->getContainer()->get('beanRegistry');

        $registry->registerBean('fakeBean', FakeBean::class);
        $instance = $registry->getBean('fakeBean');
        $this->assertInstanceOf(FakeBean::class, $instance);

        $registry->registerBean('fakeBean', FakeBean::class, '1.0');
        $instance2 = $registry->getBean('fakeBean', '1.0');
        $this->assertInstanceOf(FakeBean::class, $instance2);

        $this->assertNotSame($instance, $instance2);
    }

    public function testNotExists() {
        /** @var \YouzanCloudBoot\ExtensionPoint\BeanRegistry $registry */
        $registry = $this->getApp()->getContainer()->get('beanRegistry');

        $this->expectExceptionMessage('not exists');
        $registry->getBean('helloWorld');
    }

    public function testNotExistsWithTag() {
        /** @var \YouzanCloudBoot\ExtensionPoint\BeanRegistry $registry */
        $registry = $this->getApp()->getContainer()->get('beanRegistry');

        $this->expectExceptionMessage('not exists');
        $registry->getBean('helloWorld', '1.0');
    }
}