<?php




namespace Youzan\Open\Security;

include "HttpsClient.php";
include "DataSecurityJsonMapper.php";
include "PlainResult.php";


class HttpSecretInvoker
{
    private $initUrl;

    /**
     * HttpSecretInvoker constructor.
     * @param $initUrl
     */
    public function __construct($env)
    {
        $this->initUrl = $env->getInitUrl();
    }


    public function invoke($param) {
       $response = HttpsClient::containPostJson($this->initUrl,$param);
       $mapper = new DataSecurityJsonMapper();
       return $mapper->map(json_decode($response),new PlainResult());
    }

}