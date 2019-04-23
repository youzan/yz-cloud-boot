<?php

namespace YouzanCloudBootTests\ExtensionPoint;

use YouzanCloudBoot\ExtensionPoint\BeanRegistry;
use YouzanCloudBootTests\Base\BaseTestCase;
use YouzanCloudBootTests\Stub\FakeServiceImpl;

class BeanRegistryTest extends BaseTestCase
{

    public function testRegisterNormal() {
        /** @var \YouzanCloudBoot\ExtensionPoint\BeanRegistry $registry */
        $registry = $this->getApp()->getContainer()->get('beanRegistry');
        
        $registry->registerBean('fakeBean', FakeServiceImpl::class);
        $registry->registerBean('fakeBean', FakeServiceImpl::class, '1.0');
        $registry->registerBean('fakeBean', FakeServiceImpl::class, '2.0');
        $this->assertTrue(true);
    }
    
    public function testRegisterDuplicated() {
        /** @var \YouzanCloudBoot\ExtensionPoint\BeanRegistry $registry */
        $registry = $this->getApp()->getContainer()->get('beanRegistry');

        $this->expectExceptionMessage('registered');
        $registry->registerBean('fakeBean', FakeServiceImpl::class);
        $registry->registerBean('fakeBean', FakeServiceImpl::class);
    }

    public function testRegisterDuplicatedWithTag() {
        /** @var \YouzanCloudBoot\ExtensionPoint\BeanRegistry $registry */
        $registry = $this->getApp()->getContainer()->get('beanRegistry');

        $this->expectExceptionMessage('registered');
        $registry->registerBean('fakeBean', FakeServiceImpl::class, '1.0');
        $registry->registerBean('fakeBean', FakeServiceImpl::class, '1.0');
    }

    public function testGet() {
        /** @var \YouzanCloudBoot\ExtensionPoint\BeanRegistry $registry */
        $registry = $this->getApp()->getContainer()->get('beanRegistry');

        $registry->registerBean('fakeBean', FakeServiceImpl::class);
        $instance = $registry->getBean('fakeBean');
        $this->assertInstanceOf(FakeServiceImpl::class, $instance);

        $registry->registerBean('fakeBean', FakeServiceImpl::class, '1.0');
        $instance2 = $registry->getBean('fakeBean', '1.0');
        $this->assertInstanceOf(FakeServiceImpl::class, $instance2);

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