<?php
/**
 * Created by IntelliJ IDEA.
 * User: allen
 * Date: 2019-04-08
 * Time: 15:52
 */

namespace YouzanCloudBoot\ExtensionPoint\Api\Message;

use YouzanCloudBoot\ExtensionPoint\Api\Message\Metadata\NotifyMessage;

interface MessageHandler
{
    public function handle(NotifyMessage $notifyMessage) : void;
}