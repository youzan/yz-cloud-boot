<?php

namespace YouzanCloudBoot\Daemon\Worker;

use Workerman\Lib\Timer;
use YouzanCloudBoot\Component\BaseComponent;
use YouzanCloudBoot\Daemon\Registry\IntervalTimerRegistry;
use YouzanCloudBoot\Facades\LogFacade;

class IntervalTimerWorker extends BaseComponent
{

    public function onWorkerStart($worker)
    {
        LogFacade::info('IntervalTimerWorker.onWorkerStart...');

        /** @var IntervalTimerRegistry $intervalTimerRegistry */
        $intervalTimerRegistry = $this->getContainer()->get('intervalTimerRegistry');

        foreach ($intervalTimerRegistry->getBeanPool() as $name => $item) {

            if (is_array($item) && isset($item['timeInterval']) && isset($item['callback'])) {

                $timeInterval = floatval($item['timeInterval']);
                $callback = $item['callback'];
                $args = $item['args'];
                $persistent = boolval($item['persistent']);

                if ($persistent && $timeInterval < 60) {
                    LogFacade::warn('IntervalTimerWorker.onWorkerStart.' . $name . ' the param `timeInterval` must be greater than 60');
                    continue;
                }

                if ($timeInterval > 172800) {
                    LogFacade::warn('IntervalTimerWorker.onWorkerStart.' . $name . ' the param `timeInterval` must be smaller than 172800');
                    continue;
                }

                if (!is_callable($callback)) {
                    LogFacade::warn('IntervalTimerWorker.onWorkerStart.' . $name . ' the param `callback` must be a callback');
                    continue;
                }

                LogFacade::info('IntervalTimerWorker.onWorkerStart.' . $name);
                Timer::add($timeInterval, $callback, $args, $persistent);
            }

        }
    }


}