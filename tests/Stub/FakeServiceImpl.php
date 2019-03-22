<?php

namespace YouzanCloudBootTests\Stub;

use YouzanCloudBoot\ExtensionPoint\BaseBusinessExtensionPointImpl;
use YouzanCloudBootTests\Stub\ExtensionPoint\BizTestService;
use YouzanCloudBootTests\Stub\ExtensionPoint\Param\BizTestOutParam;
use YouzanCloudBootTests\Stub\ExtensionPoint\Param\BizTestRequest;

class FakeServiceImpl extends BaseBusinessExtensionPointImpl implements BizTestService
{

    public function invoke(BizTestRequest $bizTestRequest): BizTestOutParam
    {
        return new BizTestOutParam();
    }
}

