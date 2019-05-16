<?php

namespace YouzanCloudBootTests\Stub\ExtensionPoint\Param;

use DateTime;

class BizTestSecondaryData
{

    /**
     * @var boolean
     */
    private $boolean;

    /**
     * @var string
     */
    private $string;

    /**
     * @var integer
     */
    private $integer;

    /**
     * @var double
     */
    private $double;

    /**
     * @var \stdClass
     */
    private $anonymousObject;

    /**
     * @var string[]
     */
    private $listOfString;

    /**
     * @var \stdClass[]
     */
    private $listOfAnonymousObject;

    /**
     * @var DateTime
     */
    private $testDate;

    /**
     * @return string
     */
    public function getString(): string
    {
        return $this->string;
    }

    /**
     * @param string $string
     */
    public function setString(string $string): void
    {
        $this->string = $string;
    }

    /**
     * @return int
     */
    public function getInteger(): int
    {
        return $this->integer;
    }

    /**
     * @param int $integer
     */
    public function setInteger(int $integer): void
    {
        $this->integer = $integer;
    }

    /**
     * @return float
     */
    public function getDouble(): float
    {
        return $this->double;
    }

    /**
     * @param float $double
     */
    public function setDouble(float $double): void
    {
        $this->double = $double;
    }

    /**
     * @return \stdClass
     */
    public function getAnonymousObject(): \stdClass
    {
        return $this->anonymousObject;
    }

    /**
     * @param \stdClass $anonymousObject
     */
    public function setAnonymousObject(\stdClass $anonymousObject): void
    {
        $this->anonymousObject = $anonymousObject;
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
    public function getListOfAnonymousObject(): array
    {
        return $this->listOfAnonymousObject;
    }

    /**
     * @param \stdClass[] $listOfAnonymousObject
     */
    public function setListOfAnonymousObject(array $listOfAnonymousObject): void
    {
        $this->listOfAnonymousObject = $listOfAnonymousObject;
    }

    /**
     * @return bool
     */
    public function isBoolean(): bool
    {
        return $this->boolean;
    }

    /**
     * @param bool $boolean
     */
    public function setBoolean(bool $boolean): void
    {
        $this->boolean = $boolean;
    }

    /**
     * @return DateTime
     */
    public function getTestDate(): DateTime
    {
        return $this->testDate;
    }

    /**
     * @param DateTime $testDate
     */
    public function setTestDate(DateTime $testDate): void
    {
        $this->testDate = $testDate;
    }

}