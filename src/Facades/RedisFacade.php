<?php


namespace YouzanCloudBoot\Facades;


/**
 * YZCRedis 静态代理
 * 在标准实现下，实际上所有静态方法会被转发到一个 \Redis 类的实例
 * 请参考 * @see \Redis
 *
 * @method static psetex($key, $ttl, $value) ;
 * @method static sScan($key, $iterator, $pattern = '', $count = 0) ;
 * @method static scan(&$iterator, $pattern = null, $count = 0) ;
 * @method static zScan($key, $iterator, $pattern = '', $count = 0) ;
 * @method static hScan($key, $iterator, $pattern = '', $count = 0) ;
 * @method static client($command, $arg = '') ;
 * @method static slowlog($command) ;
 * @method static open($host, $port = 6379, $timeout = 0.0, $retry_interval = 0, $read_timeout = 0.0) ;
 * @method static pconnect($host, $port = 6379, $timeout = 0.0, $persistent_id = null, $retry_interval = 0, $read_timeout = 0.0) ;
 * @method static popen($host, $port = 6379, $timeout = 0.0, $persistent_id = null, $retry_interval = 0, $read_timeout = 0.0) ;
 * @method static close() ;
 * @method static setOption($name, $value) ;
 * @method static getOption($name) ;
 * @method static ping() ;
 * @method static echo ($message) ;
 * @method static get($key) ;
 * @method static set($key, $value, $timeout = 0) ;
 * @method static setex($key, $ttl, $value) ;
 * @method static setnx($key, $value) ;
 * @method static del($key1, ...$otherKeys) ;
 * @method static delete($key1, $key2 = null, $key3 = null) ;
 * @method static multi($mode = RedisFacade::MULTI) ;
 * @method static exec() ;
 * @method static discard() ;
 * @method static watch($key) ;
 * @method static unwatch() ;
 * @method static subscribe($channels, $callback) ;
 * @method static psubscribe($patterns, $callback) ;
 * @method static publish($channel, $message) ;
 * @method static pubsub($keyword, $argument) ;
 * @method static exists($key) ;
 * @method static incr($key) ;
 * @method static incrByFloat($key, $increment) ;
 * @method static incrBy($key, $value) ;
 * @method static decr($key) ;
 * @method static decrBy($key, $value) ;
 * @method static getMultiple(array $keys) ;
 * @method static lPush($key, $value1, $value2 = null, $valueN = null) ;
 * @method static rPush($key, $value1, $value2 = null, $valueN = null) ;
 * @method static lPushx($key, $value) ;
 * @method static rPushx($key, $value) ;
 * @method static lPop($key) ;
 * @method static rPop($key) ;
 * @method static blPop($keys, $timeout) ;
 * @method static brPop($keys, $timeout) ;
 * @method static lLen($key) ;
 * @method static lSize($key) ;
 * @method static lIndex($key, $index) ;
 * @method static lGet($key, $index) ;
 * @method static lSet($key, $index, $value) ;
 * @method static lRange($key, $start, $end) ;
 * @method static lGetRange($key, $start, $end) ;
 * @method static lTrim($key, $start, $stop) ;
 * @method static listTrim($key, $start, $stop) ;
 * @method static lRem($key, $value, $count) ;
 * @method static lRemove($key, $value, $count) ;
 * @method static lInsert($key, $position, $pivot, $value) ;
 * @method static sAdd($key, $value1, $value2 = null, $valueN = null) ;
 * @method static sAddArray($key, array $values) ;
 * @method static sRem($key, $member1, $member2 = null, $memberN = null) ;
 * @method static sRemove($key, $member1, $member2 = null, $memberN = null) ;
 * @method static sMove($srcKey, $dstKey, $member) ;
 * @method static sIsMember($key, $value) ;
 * @method static sContains($key, $value) ;
 * @method static sCard($key) ;
 * @method static sPop($key) ;
 * @method static sRandMember($key, $count = null) ;
 * @method static sInter($key1, $key2, $keyN = null) ;
 * @method static sInterStore($dstKey, $key1, $key2, $keyN = null) ;
 * @method static sUnion($key1, $key2, $keyN = null) ;
 * @method static sUnionStore($dstKey, $key1, $key2, $keyN = null) ;
 * @method static sDiff($key1, $key2, $keyN = null) ;
 * @method static sDiffStore($dstKey, $key1, $key2, $keyN = null) ;
 * @method static sMembers($key) ;
 * @method static sGetMembers($key) ;
 * @method static getSet($key, $value) ;
 * @method static randomKey() ;
 * @method static select($dbindex) ;
 * @method static move($key, $dbindex) ;
 * @method static rename($srcKey, $dstKey) ;
 * @method static renameKey($srcKey, $dstKey) ;
 * @method static renameNx($srcKey, $dstKey) ;
 * @method static expire($key, $ttl) ;
 * @method static pExpire($key, $ttl) ;
 * @method static setTimeout($key, $ttl) ;
 * @method static expireAt($key, $timestamp) ;
 * @method static pExpireAt($key, $timestamp) ;
 * @method static keys($pattern) ;
 * @method static getKeys($pattern) ;
 * @method static dbSize() ;
 * @method static auth($password) ;
 * @method static bgrewriteaof() ;
 * @method static slaveof($host = '127.0.0.1', $port = 6379) ;
 * @method static object($string = '', $key = '') ;
 * @method static save() ;
 * @method static bgsave() ;
 * @method static lastSave() ;
 * @method static wait($numSlaves, $timeout) ;
 * @method static type($key) ;
 * @method static append($key, $value) ;
 * @method static getRange($key, $start, $end) ;
 * @method static substr($key, $start, $end) ;
 * @method static setRange($key, $offset, $value) ;
 * @method static strlen($key) ;
 * @method static bitpos($key, $bit, $start = 0, $end = null) ;
 * @method static getBit($key, $offset) ;
 * @method static setBit($key, $offset, $value) ;
 * @method static bitCount($key) ;
 * @method static bitOp($operation, $retKey, ...$keys) ;
 * @method static flushDB() ;
 * @method static flushAll() ;
 * @method static sort($key, $option = null) ;
 * @method static info($option = null) ;
 * @method static resetStat() ;
 * @method static ttl($key) ;
 * @method static pttl($key) ;
 * @method static persist($key) ;
 * @method static mset(array $array) ;
 * @method static mget(array $array) ;
 * @method static msetnx(array $array) ;
 * @method static rpoplpush($srcKey, $dstKey) ;
 * @method static brpoplpush($srcKey, $dstKey, $timeout) ;
 * @method static zAdd($key, $score1, $value1, $score2 = null, $value2 = null, $scoreN = null, $valueN = null) ;
 * @method static zRange($key, $start, $end, $withscores = null) ;
 * @method static zRem($key, $member1, $member2 = null, $memberN = null) ;
 * @method static zDelete($key, $member1, $member2 = null, $memberN = null) ;
 * @method static zRevRange($key, $start, $end, $withscore = null) ;
 * @method static zRangeByScore($key, $start, $end, array $options = array()) ;
 * @method static zRevRangeByScore($key, $start, $end, array $options = array()) ;
 * @method static zRangeByLex($key, $min, $max, $offset = null, $limit = null) ;
 * @method static zRevRangeByLex($key, $min, $max, $offset = null, $limit = null) ;
 * @method static zCount($key, $start, $end) ;
 * @method static zRemRangeByScore($key, $start, $end) ;
 * @method static zDeleteRangeByScore($key, $start, $end) ;
 * @method static zRemRangeByRank($key, $start, $end) ;
 * @method static zDeleteRangeByRank($key, $start, $end) ;
 * @method static zCard($key) ;
 * @method static zSize($key) ;
 * @method static zScore($key, $member) ;
 * @method static zRank($key, $member) ;
 * @method static zRevRank($key, $member) ;
 * @method static zIncrBy($key, $value, $member) ;
 * @method static zUnion($Output, $ZSetKeys, array $Weights = null, $aggregateFunction = 'SUM') ;
 * @method static zInter($Output, $ZSetKeys, array $Weights = null, $aggregateFunction = 'SUM') ;
 * @method static hSet($key, $hashKey, $value) ;
 * @method static hSetNx($key, $hashKey, $value) ;
 * @method static hGet($key, $hashKey) ;
 * @method static hLen($key) ;
 * @method static hDel($key, $hashKey1, $hashKey2 = null, $hashKeyN = null) ;
 * @method static hKeys($key) ;
 * @method static hVals($key) ;
 * @method static hGetAll($key) ;
 * @method static hExists($key, $hashKey) ;
 * @method static hIncrBy($key, $hashKey, $value) ;
 * @method static hIncrByFloat($key, $field, $increment) ;
 * @method static hMSet($key, $hashKeys) ;
 * @method static hMGet($key, $hashKeys) ;
 * @method static config($operation, $key, $value) ;
 * @method static evaluate($script, $args = array(), $numKeys = 0) ;
 * @method static evalSha($scriptSha, $args = array(), $numKeys = 0) ;
 * @method static evaluateSha($scriptSha, $args = array(), $numKeys = 0) ;
 * @method static script($command, $script) ;
 * @method static getLastError() ;
 * @method static clearLastError() ;
 * @method static dump($key) ;
 * @method static restore($key, $ttl, $value) ;
 * @method static migrate($host, $port, $key, $db, $timeout, $copy = false, $replace = false) ;
 * @method static time() ;
 * @method static pfAdd($key, array $elements) ;
 * @method static pfCount($key) ;
 * @method static pfMerge($destkey, array $sourcekeys) ;
 * @method static rawCommand($command, $arguments) ;
 * @method static getMode() ;
 * @method static xAck($stream, $group, $arr_messages) ;
 * @method static xAdd($str_key, $str_id, $arr_message) ;
 * @method static xClaim($str_key, $str_group, $str_consumer, $min_idle_time, $arr_ids, $arr_options = []) ;
 * @method static xDel($str_key, $arr_ids) ;
 * @method static xGroup($operation, $str_key, $str_group, $str_msg_id) ;
 * @method static xInfo($operation, $str_stream, $str_group) ;
 * @method static xLen($str_stream) ;
 * @method static xPending($str_stream, $str_group, $str_start = null, $str_end = null, $i_count = null, $str_consumer = null) ;
 * @method static xRange($str_stream, $str_start, $str_end, $i_count = null) ;
 * @method static xRead($arr_streams, $i_count = null, $i_block = null) ;
 * @method static xReadGroup($str_group, $str_consumer, $arr_streams, $i_count, $i_block = null) ;
 * @method static xRevRange($str_stream, $str_end, $str_start, $i_count = null) ;
 * @method static xTrim($str_stream, $i_max_len, $boo_approximate) ;
 */
class RedisFacade extends Facade
{

    /**
     * 给静态代理设置服务名称
     * 子类必须覆盖这个方法
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'yzcRedis';
    }
}