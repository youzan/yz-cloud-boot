<?php


namespace YouzanCloudBoot\Log;


class SkynetLog implements \JsonSerializable
{
    /**
     *
     * @var string
     */
    private $tag;

    /**
     *
     * @var string
     */
    private $app;

    /**
     *
     * @var string
     */
    private $level;

    /**
     *
     * @var string
     */
    private $platform;

    /**
     *
     * @var SkynetLogDetail
     */
    private $detail;

    /**
     * @return SkynetLogDetail
     */
    public function getDetail(): SkynetLogDetail
    {
        return $this->detail;
    }

    /**
     * @param SkynetLogDetail $detail
     */
    public function setDetail(SkynetLogDetail $detail): void
    {
        $this->detail = $detail;
    }

    /**
     * @return string
     */
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     */
    public function setTag(string $tag): void
    {
        $this->tag = $tag;
    }

    /**
     * @return string
     */
    public function getApp(): string
    {
        return $this->app;
    }

    /**
     * @param string $app
     */
    public function setApp(string $app): void
    {
        $this->app = $app;
    }

    /**
     * @return string
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * @param string $level
     */
    public function setLevel(string $level): void
    {
        $this->level = $level;
    }

    /**
     * @return string
     */
    public function getPlatform(): string
    {
        return $this->platform;
    }

    /**
     * @param string $platform
     */
    public function setPlatform(string $platform): void
    {
        $this->platform = $platform;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}