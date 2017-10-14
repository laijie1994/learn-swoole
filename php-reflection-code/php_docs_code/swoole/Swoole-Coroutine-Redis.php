<?php
/**
 * Swoole\Coroutine\Redis Document
 *
 * @author Leelmes <i@chengxuan.li>
 */

namespace Swoole\Coroutine;
class Redis
{

    public $errCode = 0;
    public $errMsg = '';

    public function __construct()
    {
    }

    public function __destruct()
    {
    }

    public function connect()
    {
    }

    public function setDefer()
    {
    }

    public function getDefer()
    {
    }

    public function recv()
    {
    }

    public function close()
    {
    }

    public function set()
    {
    }

    public function setBit()
    {
    }

    public function setEx()
    {
    }

    public function psetEx()
    {
    }

    public function lSet()
    {
    }

    public function get()
    {
    }

    public function mGet()
    {
    }

    public function del()
    {
    }

    public function hDel()
    {
    }

    public function hSet()
    {
    }

    public function hMSet()
    {
    }

    public function hSetNx()
    {
    }

    public function delete()
    {
    }

    public function mSet()
    {
    }

    public function mSetNx()
    {
    }

    public function getKeys()
    {
    }

    public function keys()
    {
    }

    public function exists()
    {
    }

    public function type()
    {
    }

    public function strLen()
    {
    }

    public function lPop()
    {
    }

    public function blPop()
    {
    }

    public function rPop()
    {
    }

    public function brPop()
    {
    }

    public function bRPopLPush()
    {
    }

    public function lSize()
    {
    }

    public function lLen()
    {
    }

    public function sSize()
    {
    }

    public function scard()
    {
    }

    public function sPop()
    {
    }

    public function sMembers()
    {
    }

    public function sGetMembers()
    {
    }

    public function sRandMember()
    {
    }

    public function persist()
    {
    }

    public function ttl()
    {
    }

    public function pttl()
    {
    }

    public function zCard()
    {
    }

    public function zSize()
    {
    }

    public function hLen()
    {
    }

    public function hKeys()
    {
    }

    public function hVals()
    {
    }

    public function hGetAll()
    {
    }

    public function debug()
    {
    }

    public function restore()
    {
    }

    public function dump()
    {
    }

    public function renameKey()
    {
    }

    public function rename()
    {
    }

    public function renameNx()
    {
    }

    public function rpoplpush()
    {
    }

    public function randomKey()
    {
    }

    public function ping()
    {
    }

    public function auth()
    {
    }

    public function unwatch()
    {
    }

    public function watch()
    {
    }

    public function save()
    {
    }

    public function bgSave()
    {
    }

    public function lastSave()
    {
    }

    public function flushDB()
    {
    }

    public function flushAll()
    {
    }

    public function dbSize()
    {
    }

    public function bgrewriteaof()
    {
    }

    public function time()
    {
    }

    public function role()
    {
    }

    public function setRange()
    {
    }

    public function setNx()
    {
    }

    public function getSet()
    {
    }

    public function append()
    {
    }

    public function lPushx()
    {
    }

    public function lPush()
    {
    }

    public function rPush()
    {
    }

    public function rPushx()
    {
    }

    public function sContains()
    {
    }

    public function sismember()
    {
    }

    public function zScore()
    {
    }

    public function zRank()
    {
    }

    public function zRevRank()
    {
    }

    public function hGet()
    {
    }

    public function hMGet()
    {
    }

    public function hExists()
    {
    }

    public function publish()
    {
    }

    public function zIncrBy()
    {
    }

    public function zAdd()
    {
    }

    public function zDeleteRangeByScore()
    {
    }

    public function zRemRangeByScore()
    {
    }

    public function zCount()
    {
    }

    public function zRange()
    {
    }

    public function zRevRange()
    {
    }

    public function zRangeByScore()
    {
    }

    public function zRevRangeByScore()
    {
    }

    public function zRangeByLex()
    {
    }

    public function zRevRangeByLex()
    {
    }

    public function zInter()
    {
    }

    public function zinterstore()
    {
    }

    public function zUnion()
    {
    }

    public function zunionstore()
    {
    }

    public function incrBy()
    {
    }

    public function hIncrBy()
    {
    }

    public function incr()
    {
    }

    public function decrBy()
    {
    }

    public function decr()
    {
    }

    public function getBit()
    {
    }

    public function lInsert()
    {
    }

    public function lGet()
    {
    }

    public function lIndex()
    {
    }

    public function setTimeout()
    {
    }

    public function expire()
    {
    }

    public function pexpire()
    {
    }

    public function expireAt()
    {
    }

    public function pexpireAt()
    {
    }

    public function move()
    {
    }

    public function select()
    {
    }

    public function getRange()
    {
    }

    public function listTrim()
    {
    }

    public function ltrim()
    {
    }

    public function lGetRange()
    {
    }

    public function lRange()
    {
    }

    public function lRem()
    {
    }

    public function lRemove()
    {
    }

    public function zDeleteRangeByRank()
    {
    }

    public function zRemRangeByRank()
    {
    }

    public function incrByFloat()
    {
    }

    public function hIncrByFloat()
    {
    }

    public function bitCount()
    {
    }

    public function bitOp()
    {
    }

    public function sAdd()
    {
    }

    public function sMove()
    {
    }

    public function sDiff()
    {
    }

    public function sDiffStore()
    {
    }

    public function sUnion()
    {
    }

    public function sUnionStore()
    {
    }

    public function sInter()
    {
    }

    public function sInterStore()
    {
    }

    public function sRemove()
    {
    }

    public function srem()
    {
    }

    public function zDelete()
    {
    }

    public function zRemove()
    {
    }

    public function zRem()
    {
    }

    public function pSubscribe()
    {
    }

    public function subscribe()
    {
    }

    public function multi()
    {
    }

    public function exec()
    {
    }
}
