<?php


namespace YouzanCloudBoot\Log;
use Monolog\Processor\ProcessorInterface;
use Psr\Container\ContainerInterface;
use YouzanCloudBoot\Helper\Trace;

class YouzanSkynetProcessor implements ProcessorInterface
{
    private $_container;
    public function __construct(ContainerInterface $container)
    {
        $this->_container = $container;
    }

    public function __invoke(array $record): array
    {
        $envUtil = $this->_container->get('envUtil');
        $record['extra']['app_name'] = $envUtil->getAppName();
        $record['extra']['index_name'] = $envUtil->get('logging.track.topic');
        $record['extra']['level_name'] = strtolower($record['level_name']);
        $record['extra']['x-cat-trace'] = Trace::gen();

        $skynetLog = new SkynetLog();
        $skynetLog->setApp($envUtil->getAppName());
        $skynetLog->setLevel(strtolower($record['level_name']));
        $skynetLog->setPlatform('Php');
        $skynetLog->setTag($record['message']);

        $skynetEnvs = $_SERVER['SKYNET_ENVS'];
        if (isset($skynetEnvs)) {
            $skynetLogDetail = new SkynetLogDetail();
            $skynetLogDetail->setEnvs($skynetEnvs);
            $skynetLog->setDetail($skynetLogDetail);
        }

        $record['extra']['skynet_log'] = json_encode($skynetLog);
        return $record;
    }
}