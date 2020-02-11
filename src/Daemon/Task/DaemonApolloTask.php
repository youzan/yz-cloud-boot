<?php

namespace YouzanCloudBoot\Daemon\Task;

use YouzanCloudBoot\Component\BaseComponent;
use YouzanCloudBoot\Facades\LogFacade;
use YouzanCloudBoot\Util\ApolloUtil;

class DaemonApolloTask extends BaseComponent
{

    public function handle(): void
    {
        LogFacade::info("DaemonApolloTask begin...");

        /** @var ApolloUtil $apollo */
        $apollo = self::getContainer()->get('apolloUtil');
        $apollo->writeToFile();

        LogFacade::info("DaemonApolloTask end...");
    }

}