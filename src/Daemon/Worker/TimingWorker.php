<?php

namespace YouzanCloudBoot\Daemon\Worker;

use Workerman\Lib\Timer;
use YouzanCloudBoot\Component\BaseComponent;
use YouzanCloudBoot\Daemon\Registry\TimingRegistry;
use YouzanCloudBoot\Facades\LogFacade;

class TimingWorker extends BaseComponent
{

    public function onWorkerStart($worker)
    {
        LogFacade::info('TimingWorker.onWorkerStart...');

        /** @var TimingRegistry $timingRegistry */
        $timingRegistry = $this->getContainer()->get('timingRegistry');

        foreach ($timingRegistry->getBeanPool() as $name => $item) {
            LogFacade::info('TimingWorker.onWorkerStart.' . $name);
            Timer::add($item['timeInterval'], $item['callback']);
        }
    }


}