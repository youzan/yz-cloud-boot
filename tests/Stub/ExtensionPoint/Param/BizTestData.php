<?php

namespace YouzanCloudBootTests\Stub\ExtensionPoint\Param;

class BizTestData
{

    /**
     * The test number
     *
     * @var int
     */
    private $number;

    /**
     * The request content
     *
     * @var string
     */
    private $content;

    /**
     * @var BizTestSecondaryData
     */
    private $secondaryData;

    /**
     * @var BizTestSecondaryData[]
     */
    private $secondaryDataList;

    /**
     * @var BizTestSecondaryData[][][]
     */
    private $multiLevelList;

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @param int $number
     */
    public function setNumber(int $number): void
    {
        $this->number = $number;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return BizTestSecondaryData
     */
    public function getSecondaryData(): BizTestSecondaryData
    {
        return $this->secondaryData;
    }

    /**
     * @param BizTestSecondaryData $secondaryData
     */
    public function setSecondaryData(BizTestSecondaryData $secondaryData): void
    {
        $this->secondaryData = $secondaryData;
    }

    /**
     * @return BizTestSecondaryData[]
     */
    public function getSecondaryDataList(): array
    {
        return $this->secondaryDataList;
    }

    /**
     * @param BizTestSecondaryData[] $secondaryDataList
     */
    public function setSecondaryDataList(array $secondaryDataList): void
    {
        $this->secondaryDataList = $secondaryDataList;
    }

    /**
     * @return BizTestSecondaryData[][][]
     */
    public function getMultiLevelList(): array
    {
        return $this->multiLevelList;
    }

    /**
     * @param BizTestSecondaryData[][][] $multiLevelList
     */
    public function setMultiLevelList(array $multiLevelList): void
    {
        $this->multiLevelList = $multiLevelList;
    }
}