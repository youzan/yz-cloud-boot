<?php

namespace YouzanCloudBootTests\Util;

use YouzanCloudBoot\Util\EnvUtil;
use YouzanCloudBootTests\Base\BaseTestCase;

class EnvUtilTest extends BaseTestCase
{

    public function test()
    {

        /** @var EnvUtil $util */
        $util = $this->getApp()->getContainer()->get('envUtil');

        $this->assertNotEmpty($util->get('USER'));
        $this->assertNull($util->get('___nothing__'));
    }

    public function testFacade()
    {
        $this->assertNotEmpty(\YouzanCloudBoot\Facades\EnvUtil::get('USER'));
        $this->assertNull(\YouzanCloudBoot\Facades\EnvUtil::get('Key_Should_Not_Exists'));
    }


}