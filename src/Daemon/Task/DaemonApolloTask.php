<?php

namespace YouzanCloudBoot\Daemon\Task;

use YouzanCloudBoot\Component\BaseComponent;
use YouzanCloudBoot\Util\ApolloUtil;

class DaemonApolloTask extends BaseComponent
{

    public function handle(): void
    {
        /** @var ApolloUtil $apollo */
        $apollo = self::getContainer()->get('apolloUtil');
        $apollo->writeToFile();
    }

}