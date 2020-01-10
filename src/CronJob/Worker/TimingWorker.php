<?php

namespace YouzanCloudBoot\CronJob\Worker;

use Workerman\Lib\Timer;
use YouzanCloudBoot\Component\BaseComponent;
use YouzanCloudBoot\CronJob\Registry\TimingRegistry;
use YouzanCloudBoot\Facades\LogFacade;

class TimingWorker extends BaseComponent
{

    public function onWorkerStart($worker)
    {
        LogFacade::info('TimingWorker.onWorkerStart...');

        /** @var TimingRegistry $timingRegistry */
        $timingRegistry = $this->getContainer()->get('timingRegistry');

        foreach ($timingRegistry->getBeanPool() as $name => $item) {

            if (is_array($item) && isset($item['timeInterval']) && isset($item['callback'])) {

                $timeInterval = intval($item['timeInterval']);
                $callback = $item['callback'];

                if ($timeInterval < 60) {
                    LogFacade::warn('TimingWorker.onWorkerStart.' . $name . ' the param `timeInterval` must be greater than 60');
                    continue;
                }

                if ($timeInterval > 172800) {
                    LogFacade::warn('TimingWorker.onWorkerStart.' . $name . ' the param `timeInterval` must be smaller than 172800');
                    continue;
                }

                if (!is_callable($callback)) {
                    LogFacade::warn('TimingWorker.onWorkerStart.' . $name . ' the param `callback` must be a callback');
                    continue;
                }

                LogFacade::info('TimingWorker.onWorkerStart.' . $name);
                Timer::add($timeInterval, $callback);
            }

        }
    }


}