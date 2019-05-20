<?php


namespace YouzanCloudBoot\Facades;


/**
 * YZCRedis 静态代理
 * 在标准实现下，实际上所有静态方法会被转发到一个 \Redis 类的实例
 * 请参考 * @see \Redis
 *
 * @method static bool psetex($key, $ttl, $value) ;
 * @method static array|bool sScan($key, $iterator, $pattern = '', $count = 0) ;
 * @method static array|bool scan(&$iterator, $pattern = null, $count = 0) ;
 * @method static array|bool zScan($key, $iterator, $pattern = '', $count = 0) ;
 * @method static array hScan($key, $iterator, $pattern = '', $count = 0) ;
 * @method static mixed client($command, $arg = '') ;
 * @method static mixed slowlog($command) ;
 * @method static bool close() ;
 * @method static bool setOption($name, $value) ;
 * @method static int getOption($name) ;
 * @method static string ping() ;
 * @method static string echo($message) ;
 * @method static string|bool get($key) ;
 * @method static bool set($key, $value, $timeout = 0) ;
 * @method static bool setex($key, $ttl, $value) ;
 * @method static bool setnx($key, $value) ;
 * @method static int del($key1, ...$otherKeys) ;
 * @method static int delete($key1, $key2 = null, $key3 = null) ;
 * @method static \Redis multi($mode = \Redis::MULTI) ;
 * @method static array exec() ;
 * @method static \Redis discard() ;
 * @method static void watch($key) ;
 * @method static void unwatch() ;
 * @method static mixed subscribe($channels, $callback) ;
 * @method static mixed psubscribe($patterns, $callback) ;
 * @method static int publish($channel, $message) ;
 * @method static array|int pubsub($keyword, $argument) ;
 * @method static int exists($key) ;
 * @method static int incr($key) ;
 * @method static float incrByFloat($key, $increment) ;
 * @method static int incrBy($key, $value) ;
 * @method static int decr($key) ;
 * @method static int decrBy($key, $value) ;
 * @method static array getMultiple(array $keys) ;
 * @method static int|bool lPush($key, $value1, $value2 = null, $valueN = null) ;
 * @method static int|bool rPush($key, $value1, $value2 = null, $valueN = null) ;
 * @method static int lPushx($key, $value) ;
 * @method static int rPushx($key, $value) ;
 * @method static string lPop($key) ;
 * @method static string rPop($key) ;
 * @method static array blPop($keys, $timeout) ;
 * @method static array brPop($keys, $timeout) ;
 * @method static int lLen($key) ;
 * @method static int lSize($key) ;
 * @method static string|false lIndex($key, $index) ;
 * @method static string|false lGet($key, $index) ;
 * @method static bool lSet($key, $index, $value) ;
 * @method static array lRange($key, $start, $end) ;
 * @method static array lGetRange($key, $start, $end) ;
 * @method static array lTrim($key, $start, $stop) ;
 * @method static array listTrim($key, $start, $stop) ;
 * @method static int lRem($key, $value, $count) ;
 * @method static int lRemove($key, $value, $count) ;
 * @method static int lInsert($key, $position, $pivot, $value) ;
 * @method static int sAdd($key, $value1, $value2 = null, $valueN = null) ;
 * @method static boolean sAddArray($key, array $values) ;
 * @method static int sRem($key, $member1, $member2 = null, $memberN = null) ;
 * @method static int sRemove($key, $member1, $member2 = null, $memberN = null) ;
 * @method static bool sMove($srcKey, $dstKey, $member) ;
 * @method static bool sIsMember($key, $value) ;
 * @method static bool sContains($key, $value) ;
 * @method static int sCard($key) ;
 * @method static string|false sPop($key) ;
 * @method static string|array sRandMember($key, $count = null) ;
 * @method static array sInter($key1, $key2, $keyN = null) ;
 * @method static int sInterStore($dstKey, $key1, $key2, $keyN = null) ;
 * @method static array sUnion($key1, $key2, $keyN = null) ;
 * @method static int sUnionStore($dstKey, $key1, $key2, $keyN = null) ;
 * @method static array sDiff($key1, $key2, $keyN = null) ;
 * @method static int sDiffStore($dstKey, $key1, $key2, $keyN = null) ;
 * @method static array sMembers($key) ;
 * @method static array sGetMembers($key) ;
 * @method static string getSet($key, $value) ;
 * @method static string randomKey() ;
 * @method static bool select($dbindex) ;
 * @method static bool move($key, $dbindex) ;
 * @method static bool rename($srcKey, $dstKey) ;
 * @method static bool renameKey($srcKey, $dstKey) ;
 * @method static bool renameNx($srcKey, $dstKey) ;
 * @method static bool expire($key, $ttl) ;
 * @method static bool pExpire($key, $ttl) ;
 * @method static bool setTimeout($key, $ttl) ;
 * @method static bool expireAt($key, $timestamp) ;
 * @method static bool pExpireAt($key, $timestamp) ;
 * @method static array keys($pattern) ;
 * @method static array getKeys($pattern) ;
 * @method static int dbSize() ;
 * @method static bool auth($password) ;
 * @method static bool bgrewriteaof() ;
 * @method static bool slaveof($host = '127.0.0.1', $port = 6379) ;
 * @method static string|false object($string = '', $key = '') ;
 * @method static bool save() ;
 * @method static bool bgsave() ;
 * @method static int lastSave() ;
 * @method static int wait($numSlaves, $timeout) ;
 * @method static int type($key) ;
 * @method static int append($key, $value) ;
 * @method static string getRange($key, $start, $end) ;
 * @method static string substr($key, $start, $end) ;
 * @method static string setRange($key, $offset, $value) ;
 * @method static int strlen($key) ;
 * @method static int bitpos($key, $bit, $start = 0, $end = null) ;
 * @method static int getBit($key, $offset) ;
 * @method static int setBit($key, $offset, $value) ;
 * @method static int bitCount($key) ;
 * @method static int bitOp($operation, $retKey, ...$keys) ;
 * @method static bool flushDB() ;
 * @method static bool flushAll() ;
 * @method static array sort($key, $option = null) ;
 * @method static string info($option = null) ;
 * @method static bool resetStat() ;
 * @method static int ttl($key) ;
 * @method static int pttl($key) ;
 * @method static bool persist($key) ;
 * @method static bool mset(array $array) ;
 * @method static array mget(array $array) ;
 * @method static bool msetnx(array $array) ;
 * @method static string rpoplpush($srcKey, $dstKey) ;
 * @method static string brpoplpush($srcKey, $dstKey, $timeout) ;
 * @method static int zAdd($key, $score1, $value1, $score2 = null, $value2 = null, $scoreN = null, $valueN = null) ;
 * @method static array zRange($key, $start, $end, $withscores = null) ;
 * @method static int zRem($key, $member1, $member2 = null, $memberN = null) ;
 * @method static int zDelete($key, $member1, $member2 = null, $memberN = null) ;
 * @method static array zRevRange($key, $start, $end, $withscore = null) ;
 * @method static array zRangeByScore($key, $start, $end, array $options = array()) ;
 * @method static array zRevRangeByScore($key, $start, $end, array $options = array()) ;
 * @method static array zRangeByLex($key, $min, $max, $offset = null, $limit = null) ;
 * @method static array zRevRangeByLex($key, $min, $max, $offset = null, $limit = null) ;
 * @method static int zCount($key, $start, $end) ;
 * @method static int zRemRangeByScore($key, $start, $end) ;
 * @method static int zDeleteRangeByScore($key, $start, $end) ;
 * @method static int zRemRangeByRank($key, $start, $end) ;
 * @method static int zDeleteRangeByRank($key, $start, $end) ;
 * @method static int zCard($key) ;
 * @method static int zSize($key) ;
 * @method static float zScore($key, $member) ;
 * @method static int zRank($key, $member) ;
 * @method static int zRevRank($key, $member) ;
 * @method static float zIncrBy($key, $value, $member) ;
 * @method static int zUnion($Output, $ZSetKeys, array $Weights = null, $aggregateFunction = 'SUM') ;
 * @method static int zInter($Output, $ZSetKeys, array $Weights = null, $aggregateFunction = 'SUM') ;
 * @method static int|bool hSet($key, $hashKey, $value) ;
 * @method static bool hSetNx($key, $hashKey, $value) ;
 * @method static string hGet($key, $hashKey) ;
 * @method static int hLen($key) ;
 * @method static int|bool hDel($key, $hashKey1, $hashKey2 = null, $hashKeyN = null) ;
 * @method static array hKeys($key) ;
 * @method static array hVals($key) ;
 * @method static array hGetAll($key) ;
 * @method static bool hExists($key, $hashKey) ;
 * @method static int hIncrBy($key, $hashKey, $value) ;
 * @method static float hIncrByFloat($key, $field, $increment) ;
 * @method static bool hMSet($key, $hashKeys) ;
 * @method static array hMGet($key, $hashKeys) ;
 * @method static array config($operation, $key, $value) ;
 * @method static mixed evaluate($script, $args = array(), $numKeys = 0) ;
 * @method static mixed evalSha($scriptSha, $args = array(), $numKeys = 0) ;
 * @method static mixed evaluateSha($scriptSha, $args = array(), $numKeys = 0) ;
 * @method static mixed script($command, $script) ;
 * @method static string|null getLastError() ;
 * @method static bool clearLastError() ;
 * @method static string dump($key) ;
 * @method static bool restore($key, $ttl, $value) ;
 * @method static bool migrate($host, $port, $key, $db, $timeout, $copy = false, $replace = false) ;
 * @method static array time() ;
 * @method static bool pfAdd($key, array $elements) ;
 * @method static int pfCount($key) ;
 * @method static bool pfMerge($destkey, array $sourcekeys) ;
 * @method static mixed rawCommand($command, $arguments) ;
 * @method static int getMode() ;
 * @method static int xAck($stream, $group, $arr_messages) ;
 * @method static string xAdd($str_key, $str_id, $arr_message) ;
 * @method static array xClaim($str_key, $str_group, $str_consumer, $min_idle_time, $arr_ids, $arr_options = []) ;
 * @method static int xDel($str_key, $arr_ids) ;
 * @method static mixed xGroup($operation, $str_key, $str_group, $str_msg_id) ;
 * @method static mixed xInfo($operation, $str_stream, $str_group) ;
 * @method static int xLen($str_stream) ;
 * @method static array xPending($str_stream, $str_group, $str_start = null, $str_end = null, $i_count = null, $str_consumer = null) ;
 * @method static array xRange($str_stream, $str_start, $str_end, $i_count = null) ;
 * @method static array xRead($arr_streams, $i_count = null, $i_block = null) ;
 * @method static array xReadGroup($str_group, $str_consumer, $arr_streams, $i_count, $i_block = null) ;
 * @method static array xRevRange($str_stream, $str_end, $str_start, $i_count = null) ;
 * @method static int xTrim($str_stream, $i_max_len, $boo_approximate) ;
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