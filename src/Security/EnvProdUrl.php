<?php


namespace YouzanCloudBoot\Security;


class EnvProdUrl extends EnvUrl
{

    public function getUrl()
    {
        return "https://open.youzanyun.com/";
    }
}