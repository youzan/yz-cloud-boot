<?php

namespace YouzanCloudBootTests\Helper;

use YouzanCloudBoot\Helper\EnvHelper;
use YouzanCloudBootTests\Base\BaseTestCase;

class EnvHelperTest extends BaseTestCase
{

    public function test() {

        /** @var EnvHelper $helper */
        $helper = $this->getApp()->getContainer()->get('envHelper');

        $this->assertNotEmpty($helper->get('USER'));
        $this->assertNull($helper->get('___nothing__'));
    }


}