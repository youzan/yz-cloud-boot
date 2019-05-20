<?php


namespace YouzanCloudBootTests\Log;


use YouzanCloudBoot\Facades\Logger;
use YouzanCloudBootTests\Base\BaseTestCase;

class LoggerTest extends BaseTestCase
{

    public function testFacade()
    {
        $this->assertTrue(Logger::info("hello, world"));
        $this->assertTrue(Logger::warn("hello, world"));
        $this->assertTrue(Logger::notice("hello, world"));
        $this->assertTrue(Logger::error("hello, world"));
    }

}