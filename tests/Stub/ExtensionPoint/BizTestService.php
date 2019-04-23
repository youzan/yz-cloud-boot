<?php

namespace YouzanCloudBootTests\Stub\ExtensionPoint;

use YouzanCloudBootTests\Stub\ExtensionPoint\Param\BizTestOutParam;
use YouzanCloudBootTests\Stub\ExtensionPoint\Param\BizTestRequest;

/**
 * Fake Service for testing
 *
 * Interface BizTestService
 * @package YouzanCloudBootTests\Stub\ExtensionPoint
 */
interface BizTestService
{

    public function invoke(BizTestRequest $bizTestRequest): BizTestOutParam;

}