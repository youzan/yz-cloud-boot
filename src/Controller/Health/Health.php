<?php


namespace YouzanCloudBoot\Controller\Health;


class Health implements \JsonSerializable
{

    /**
     * The health status
     *
     * @var string
     */
    private $status;

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function jsonSerialize() {
        return get_object_vars($this);
    }
}