<?php


namespace YouzanCloudBootTests\Facades;


use YouzanCloudBoot\Facades\Facade;
use YouzanCloudBootTests\Base\BaseTestCase;

class FacadeBaseTestCase extends BaseTestCase
{

    public function setUp()
    {
        parent::setUpBeforeClass();
        Facade::setFacadeApplication($this->getApp());
    }

}