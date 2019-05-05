<?php


namespace YouzanCloudBoot\Log;


class SkynetLogDetail implements \JsonSerializable
{
    /**
     *
     * @var string
     */
    private $envs;

    /**
     * @return string
     */
    public function getEnvs(): string
    {
        return $this->envs;
    }

    /**
     * @param string $envs
     */
    public function setEnvs(string $envs): void
    {
        $this->envs = $envs;
    }


    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}