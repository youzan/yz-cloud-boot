# YZCloudBoot

Bootstrap for YZCloud PHP Application

Based on Slim Framework: http://www.slimframework.com/

## 安装


## 使用


## 目录结构


## 依赖注入容器和默认组件

依赖注入使用了 SlimFramework 默认内建的 Pimple: https://pimple.symfony.com/

默认包含了所有 SlimFramework 的组件，可以参考: http://www.slimframework.com/docs/v3/concepts/di.html 的 **Required services**
章节

以下是我们这个框架中封装的组件：

```php
<?php
// @var \YouzanCloudBoot\Util\EnvUtil $envUtil 环境访问实用工具 
$envUtil = Di::get('envUtil');

// @var \Monolog\Logger $logger 日志
$logger = Di::get('logger');

// @var \YouzanCloudBoot\ExtensionPoint\BeanRegistry $beanRegistry 业务扩展点注册器
$beanRegistry = Di::get('beanRegistry');

// @var \YouzanCloudBoot\ExtensionPoint\TopicRegistry $topicRegistry 消息扩展点注册器
$topicRegistry = Di::get('topicRegistry');

// @var \YouzanCloudBoot\Util\ObjectBuilder $objectBuilder 反序列化辅助工具，将扩展点调用参数转换成匹配接口的对象
$objectBuilder = Di::get('objectBuilder');

// @var \YouzanCloudBoot\Store\PDOFactory $pdoFactory PDO 工厂，用于生成 PDO 实例
$pdoFactory = Di::get('pdoFactory');

// @var \YouzanCloudBoot\Store\RedisFactory $redisFactory Redis 工厂，用于生成 Redis 实例
$redisFactory = Di::get('redisFactory');

// @var \YouzanCloudBoot\Http\HttpClientWrapper $httpClient HTTP 客户端，进行了有赞云统一接出的封装以配合白名单机制
$httpClient = Di::get('httpClient');

// @var \PDO $yzcMysql 连接有赞云上 MySQL 组件的 PDO 客户端，其采用的 MySQL 连接字符集为 utf8mb4，获得的 PDO 对象是 PHP 官方 PDO 对象
$yzcMysql = Di::get('yzcMysql');

// @var \Redis $yzcRedis 连接有赞云上 Redis 组件的 Redis 客户端，获得的 Redis 对象是 php-redis 扩展提供的标准 Redis 对象 象
$yzcRedis = Di::get('yzcRedis');
```

