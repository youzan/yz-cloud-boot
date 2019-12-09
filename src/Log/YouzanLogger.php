<?php


namespace YouzanCloudBoot\Log;


use Monolog\Logger;

class YouzanLogger extends Logger
{

    private const MAX_LENGTH = 10240;


    public function addRecord($level, $message, array $context = array())
    {
        if (is_string($message) && strlen($message) > self::MAX_LENGTH) {
            // 采用mb_substr按字符截断 可以解决一个中文字被截断的问题 但会可能会超出MAX_LENGTH
            // 采用substr按字节截断 可以严格限制MAX_LENGTH 但可能导致中文字被截断
            $message = substr($message, 0, self::MAX_LENGTH);
        }

        if (is_array($context) && count($context) > 0) {
            // 如果附属array超出MAX_LENGTH 直接置为空
            if (strlen(json_encode($context)) > self::MAX_LENGTH) {
                $context = array();
            }
        }

        return parent::addRecord($level, $message, $context);
    }

}