<?php


namespace YouzanCloudBoot\Constant;


class Env
{
    const APOLLO_APP_ID = 'YOUZAN_CLOUD_CONFIG_APP_ID';

    const APOLLO_APP_SECRET = 'YOUZAN_CLOUD_APOLLO_CONFIG_APP_SECRET';

    const APOLLO_META_SERVER = 'APOLLO_METASERVER';

    const APOLLO_FILE_CLOUD = '/tmp/apollo_all.yaml';

    const APOLLO_FILE_LOCAL = '/env.local.yaml';


    public static function getApolloFile(): string
    {
        if ($_ENV['YOUZAN_ENV']) {
            return self::APOLLO_FILE_CLOUD;
        }

        if (defined('YZCLOUD_BOOT_APP_DIR') && file_exists(YZCLOUD_BOOT_APP_DIR . self::APOLLO_FILE_LOCAL)) {
            return YZCLOUD_BOOT_APP_DIR . self::APOLLO_FILE_LOCAL;
        }

        return self::APOLLO_FILE_CLOUD;
    }
}