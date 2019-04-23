<?php

namespace YouzanCloudBootTests\Stub;

use YouzanCloudBoot\ExtensionPoint\BaseBusinessExtensionPointImpl;
use YouzanCloudBootTests\Stub\ExtensionPoint\BizTestService;
use YouzanCloudBootTests\Stub\ExtensionPoint\Param\BizTestOutParam;
use YouzanCloudBootTests\Stub\ExtensionPoint\Param\BizTestRequest;

class FakeServiceImpl extends BaseBusinessExtensionPointImpl implements BizTestService
{
    public static $r = 100;

    public function invoke(BizTestRequest $bizTestRequest): BizTestOutParam
    {
        $r = new BizTestOutParam();
        $r->setCode(self::$r);

        return $r;
    }
}

