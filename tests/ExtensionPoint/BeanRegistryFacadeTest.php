<?php

namespace YouzanCloudBootTests\ExtensionPoint;

use YouzanCloudBoot\Facades\BeanRegistry;
use YouzanCloudBootTests\Base\BaseTestCase;
use YouzanCloudBootTests\Stub\FakeServiceImpl;

class BeanRegistryFacadeTest extends BaseTestCase
{

    public function testRegisterNormal() {
        BeanRegistry::registerBean('fakeBean', FakeServiceImpl::class);
        BeanRegistry::registerBean('fakeBean', FakeServiceImpl::class, '1.0');
        BeanRegistry::registerBean('fakeBean', FakeServiceImpl::class, '2.0');
        $this->assertTrue(true);
    }
    
    public function testRegisterDuplicated() {
        $this->expectExceptionMessage('registered');
        BeanRegistry::registerBean('fakeBean', FakeServiceImpl::class);
        BeanRegistry::registerBean('fakeBean', FakeServiceImpl::class);
    }

    public function testRegisterDuplicatedWithTag() {
        $this->expectExceptionMessage('registered');
        BeanRegistry::registerBean('fakeBean', FakeServiceImpl::class, '1.0');
        BeanRegistry::registerBean('fakeBean', FakeServiceImpl::class, '1.0');
    }

    public function testGet() {
        BeanRegistry::registerBean('fakeBean', FakeServiceImpl::class);
        $instance = BeanRegistry::getBean('fakeBean');
        $this->assertInstanceOf(FakeServiceImpl::class, $instance);

        BeanRegistry::registerBean('fakeBean', FakeServiceImpl::class, '1.0');
        $instance2 = BeanRegistry::getBean('fakeBean', '1.0');
        $this->assertInstanceOf(FakeServiceImpl::class, $instance2);

        $this->assertNotSame($instance, $instance2);
    }

    public function testNotExists() {
        $this->expectExceptionMessage('not exists');
        BeanRegistry::getBean('helloWorld');
    }

    public function testNotExistsWithTag() {
        $this->expectExceptionMessage('not exists');
        BeanRegistry::getBean('helloWorld', '1.0');
    }
}