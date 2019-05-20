<?php

namespace YouzanCloudBootTests\ExtensionPoint;

use YouzanCloudBoot\Facades\BeanRegFacade;
use YouzanCloudBootTests\Base\BaseTestCase;
use YouzanCloudBootTests\Stub\FakeServiceImpl;

class BeanRegistryFacadeTest extends BaseTestCase
{

    public function testRegisterNormal() {
        BeanRegFacade::registerBean('fakeBean', FakeServiceImpl::class);
        BeanRegFacade::registerBean('fakeBean', FakeServiceImpl::class, '1.0');
        BeanRegFacade::registerBean('fakeBean', FakeServiceImpl::class, '2.0');
        $this->assertTrue(true);
    }
    
    public function testRegisterDuplicated() {
        $this->expectExceptionMessage('registered');
        BeanRegFacade::registerBean('fakeBean', FakeServiceImpl::class);
        BeanRegFacade::registerBean('fakeBean', FakeServiceImpl::class);
    }

    public function testRegisterDuplicatedWithTag() {
        $this->expectExceptionMessage('registered');
        BeanRegFacade::registerBean('fakeBean', FakeServiceImpl::class, '1.0');
        BeanRegFacade::registerBean('fakeBean', FakeServiceImpl::class, '1.0');
    }

    public function testGet() {
        BeanRegFacade::registerBean('fakeBean', FakeServiceImpl::class);
        $instance = BeanRegFacade::getBean('fakeBean');
        $this->assertInstanceOf(FakeServiceImpl::class, $instance);

        BeanRegFacade::registerBean('fakeBean', FakeServiceImpl::class, '1.0');
        $instance2 = BeanRegFacade::getBean('fakeBean', '1.0');
        $this->assertInstanceOf(FakeServiceImpl::class, $instance2);

        $this->assertNotSame($instance, $instance2);
    }

    public function testNotExists() {
        $this->expectExceptionMessage('not exists');
        BeanRegFacade::getBean('helloWorld');
    }

    public function testNotExistsWithTag() {
        $this->expectExceptionMessage('not exists');
        BeanRegFacade::getBean('helloWorld', '1.0');
    }
}