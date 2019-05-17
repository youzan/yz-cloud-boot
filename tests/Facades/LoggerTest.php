<?php


namespace YouzanCloudBootTests\Facades;


use YouzanCloudBoot\Facades\Logger;

class LoggerTest extends FacadeBaseTestCase
{

    public function testLogger()
    {
        $this->assertTrue(Logger::info("hello, world"));
        $this->assertTrue(Logger::warn("hello, world"));
        $this->assertTrue(Logger::notice("hello, world"));
        $this->assertTrue(Logger::error("hello, world"));
    }

}