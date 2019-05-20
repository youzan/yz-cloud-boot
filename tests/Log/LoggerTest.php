<?php


namespace YouzanCloudBootTests\Log;


use YouzanCloudBoot\Facades\LogFacade;
use YouzanCloudBootTests\Base\BaseTestCase;

class LoggerTest extends BaseTestCase
{

    public function testFacade()
    {
        $this->assertTrue(LogFacade::info("hello, world"));
        $this->assertTrue(LogFacade::warn("hello, world"));
        $this->assertTrue(LogFacade::notice("hello, world"));
        $this->assertTrue(LogFacade::error("hello, world"));
    }

}