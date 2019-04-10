<?php
/**
 * Created by IntelliJ IDEA.
 * User: allen
 * Date: 2019-02-28
 * Time: 15:41
 */

namespace YouzanCloudBoot\ExtensionPoint\Api\Message\Metadata;

/**
 * Class NotifyMessage
 * @package YouzanCloudBoot\ExtensionPoint\Api\Message\Metadata
 * 消息扩展点传输对象
 */
class NotifyMessage
{
    /**
     * 消息Topic
     * @var string
     */
    private $topic;

    /**
     * json序列化的数据，可转成特定的对象，由特定的对象转换而来
     * @var string
     */
    private $data;

    /**
     * @return string
     */
    public function getTopic(): string
    {
        return $this->topic;
    }

    /**
     * @param string $topic
     */
    public function setTopic(string $topic): void
    {
        $this->topic = $topic;
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData(string $data): void
    {
        $this->data = $data;
    }
}