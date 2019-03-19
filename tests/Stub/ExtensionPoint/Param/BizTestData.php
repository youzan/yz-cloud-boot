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
     * @var string[]
     */
    private $listOfString;

    /**
     * @var \stdClass[]
     */
    private $listOfData;

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
     * @return string[]
     */
    public function getListOfString(): array
    {
        return $this->listOfString;
    }

    /**
     * @param string[] $listOfString
     */
    public function setListOfString(array $listOfString): void
    {
        $this->listOfString = $listOfString;
    }

    /**
     * @return \stdClass[]
     */
    public function getListOfData(): array
    {
        return $this->listOfData;
    }

    /**
     * @param \stdClass[] $listOfData
     */
    public function setListOfData(array $listOfData): void
    {
        $this->listOfData = $listOfData;
    }
}