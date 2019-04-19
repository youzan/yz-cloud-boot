<?php


namespace YouzanCloudBoot\Log;
use Monolog\Processor\ProcessorInterface;

class HostnameProcessor implements ProcessorInterface
{
    private static $host;
    private static $ip;
    public function __construct()
    {
        self::$host = (string) gethostname();
        self::$ip = (string) gethostbyname(self::$host);
    }
    public function __invoke(array $record): array
    {
        $record['extra']['hostname'] = self::$host;
        $record['extra']['ip'] = self::$ip;
        return $record;
    }
}