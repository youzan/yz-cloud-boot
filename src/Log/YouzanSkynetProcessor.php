<?php


namespace YouzanCloudBoot\Log;
use Monolog\Processor\ProcessorInterface;
use Psr\Container\ContainerInterface;

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
        return $record;
    }
}