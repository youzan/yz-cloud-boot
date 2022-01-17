<?php


namespace YouzanCloudBoot\Security;


class EnvQaUrl extends EnvUrl
{

    public function getUrl()
    {
        return "http://bifrost-gateway.qa.s.qima-inc.com/";
    }
}