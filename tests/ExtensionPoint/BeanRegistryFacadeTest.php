<?php

namespace YouzanCloudBootTests\ExtensionPoint;

use YouzanCloudBoot\Facades\BepRegFacade;
use YouzanCloudBootTests\Base\BaseTestCase;
use YouzanCloudBootTests\Stub\FakeServiceImpl;

class BeanRegistryFacadeTest extends BaseTestCase
{

    public function testRegisterNormal() {
        BepRegFacade::register('fakeBean', FakeServiceImpl::class);
        BepRegFacade::register('fakeBean', FakeServiceImpl::class, '1.0');
        BepRegFacade::register('fakeBean', FakeServiceImpl::class, '2.0');
        $this->assertTrue(true);
    }
    
    public function testRegisterDuplicated() {
        $this->expectExceptionMessage('registered');
        BepRegFacade::register('fakeBean', FakeServiceImpl::class);
        BepRegFacade::register('fakeBean', FakeServiceImpl::class);
    }

    public function testRegisterDuplicatedWithTag() {
        $this->expectExceptionMessage('registered');
        BepRegFacade::register('fakeBean', FakeServiceImpl::class, '1.0');
        BepRegFacade::register('fakeBean', FakeServiceImpl::class, '1.0');
    }

    public function testGet() {
        BepRegFacade::register('fakeBean', FakeServiceImpl::class);
        $instance = BepRegFacade::getBean('fakeBean');
        $this->assertInstanceOf(FakeServiceImpl::class, $instance);

        BepRegFacade::register('fakeBean', FakeServiceImpl::class, '1.0');
        $instance2 = BepRegFacade::getBean('fakeBean', '1.0');
        $this->assertInstanceOf(FakeServiceImpl::class, $instance2);

        $this->assertNotSame($instance, $instance2);
    }

    public function testNotExists() {
        $this->expectExceptionMessage('not exists');
        BepRegFacade::getBean('helloWorld');
    }

    public function testNotExistsWithTag() {
        $this->expectExceptionMessage('not exists');
        BepRegFacade::getBean('helloWorld', '1.0');
    }
}