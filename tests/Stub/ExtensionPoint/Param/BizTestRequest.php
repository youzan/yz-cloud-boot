<?php

namespace YouzanCloudBootTests\Stub\ExtensionPoint\Param;

class BizTestRequest
{

    /**
     * @var int
     */
    private $requestId;

    /**
     * @var BizTestData
     */
    private $data;

    /**
     * @return int
     */
    public function getRequestId(): int
    {
        return $this->requestId;
    }

    /**
     * @param int $requestId
     */
    public function setRequestId(int $requestId): void
    {
        $this->requestId = $requestId;
    }

    /**
     * @return BizTestData
     */
    public function getData(): BizTestData
    {
        return $this->data;
    }

    /**
     * @param BizTestData $data
     */
    public function setData(BizTestData $data): void
    {
        $this->data = $data;
    }
}