<?php


namespace YouzanCloudBoot\Log;


use Monolog\Logger;

class YouzanLogger extends Logger
{

    private const MAX_LENGTH = 10240;


    public function addRecord($level, $message, array $context = array())
    {
        if (is_string($message) && strlen($message) > self::MAX_LENGTH) {
            $message = mb_substr($message, 0, self::MAX_LENGTH);
        }

        if (is_array($context) && count($context) > 0) {
            if (strlen(json_encode($context)) > self::MAX_LENGTH) {
                $context = array();
            }
        }

        parent::addRecord($level, $message, $context);
    }

}