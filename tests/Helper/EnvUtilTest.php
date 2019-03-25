<?php

namespace YouzanCloudBootTests\Helper;

use YouzanCloudBoot\Util\EnvUtil;
use YouzanCloudBootTests\Base\BaseTestCase;

class EnvUtilTest extends BaseTestCase
{

    public function test() {

        /** @var EnvUtil $util */
        $util = $this->getApp()->getContainer()->get('envUtil');

        $this->assertNotEmpty($util->get('USER'));
        $this->assertNull($util->get('___nothing__'));
    }


}