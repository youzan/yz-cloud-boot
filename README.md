# YZCloudBoot

Bootstrap for YZCloud PHP Application

Based on Slim Framework: http://www.slimframework.com/

## 安装


## 使用


## 目录结构


## Facade

实现了类似于 Laravel 的 Facade 特性

具体的命名空间位于 `\YouzanCloudBoot\Facades` 中

目前包括：

```
SlimFramework 原生

\YouzanCloudBoot\Facades\App
等效于 \Slim\App 的实例 $app 对象

\YouzanCloudBoot\Facades\Di
等效于 $app->getContainer();

\YouzanCloudBoot\Facades\Request
等效于 $app->getContainer()->get('request');

\YouzanCloudBoot\Facades\Response
等效于 $app->getContainer()->get('response');

\YouzanCloudBoot\Facades\Logger
等效于 $app->getContainer()->get('logger');

有赞云框架专属

\YouzanCloudBoot\Facades\BeanRegistry
等效于 $app->getContainer()->get('beanRegistry');

\YouzanCloudBoot\Facades\TopicRegistry
等效于 $app->getContainer()->get('topicRegistry');

\YouzanCloudBoot\Facades\EnvUtil
等效于 $app->getContainer()->get('envUtil');

\YouzanCloudBoot\Facades\Http
等效于 $app->getContainer()->get('httpClient');

\YouzanCloudBoot\Facades\MySQL
等效于 $app->getContainer()->get('yzcMySQL');

\YouzanCloudBoot\Facades\PDOFactory
等效于 $app->getContainer()->get('pdoFactory');

\YouzanCloudBoot\Facades\Redis
等效于 $app->getContainer()->get('yzcRedis');

\YouzanCloudBoot\Facades\RedisFactory
等效于 $app->getContainer()->get('redisFactory');
```

具体的类参考，可以参见每个类头部的 PHP Doc，在 PHPStorm 等 IDE 内可以识别 @method 并提供语法提示

## 依赖注入容器和默认组件

依赖注入使用了 SlimFramework 默认内建的 Pimple: https://pimple.symfony.com/

默认包含了所有 SlimFramework 的组件，可以参考: http://www.slimframework.com/docs/v3/concepts/di.html 的 **Required services**
章节

以下是我们这个框架中封装的组件：

```php
<?php
// @var \YouzanCloudBoot\Util\EnvUtil $envUtil 环境访问实用工具 
$envUtil = YouzanCloudBoot\Facades\Di::get('envUtil');

// @var \Monolog\Logger $logger 日志
$logger = YouzanCloudBoot\Facades\Di::get('logger');

// @var \YouzanCloudBoot\ExtensionPoint\BeanRegistry $beanRegistry 业务扩展点注册器
$beanRegistry = YouzanCloudBoot\Facades\Di::get('beanRegistry');

// @var \YouzanCloudBoot\ExtensionPoint\TopicRegistry $topicRegistry 消息扩展点注册器
$topicRegistry = YouzanCloudBoot\Facades\Di::get('topicRegistry');

// @var \YouzanCloudBoot\Util\ObjectBuilder $objectBuilder 反序列化辅助工具，将扩展点调用参数转换成匹配接口的对象
$objectBuilder = YouzanCloudBoot\Facades\Di::get('objectBuilder');

// @var \YouzanCloudBoot\Store\PDOFactory $pdoFactory PDO 工厂，用于生成 PDO 实例
$pdoFactory = YouzanCloudBoot\Facades\Di::get('pdoFactory');

// @var \YouzanCloudBoot\Store\RedisFactory $redisFactory Redis 工厂，用于生成 Redis 实例
$redisFactory = YouzanCloudBoot\Facades\Di::get('redisFactory');

// @var \YouzanCloudBoot\Http\HttpClientWrapper $httpClient HTTP 客户端，进行了有赞云统一接出的封装以配合白名单机制
$httpClient = YouzanCloudBoot\Facades\Di::get('httpClient');

// @var \PDO $yzcMysql 连接有赞云上 MySQL 组件的 PDO 客户端，其采用的 MySQL 连接字符集为 utf8mb4，获得的 PDO 对象是 PHP 官方 PDO 对象
$yzcMysql = YouzanCloudBoot\Facades\Di::get('yzcMysql');

// @var \Redis $yzcRedis 连接有赞云上 Redis 组件的 Redis 客户端，获得的 Redis 对象是 php-redis 扩展提供的标准 Redis 对象 象
$yzcRedis = YouzanCloudBoot\Facades\Di::get('yzcRedis');
```

