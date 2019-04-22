<?php

namespace YouzanCloudBoot\ExtensionPoint\Api\Message;

use YouzanCloudBoot\ExtensionPoint\Api\Message\Metadata\NotifyMessage;

interface MessageHandler
{

    public function handle(NotifyMessage $notifyMessage): void;

}