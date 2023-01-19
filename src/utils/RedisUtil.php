<?php

namespace ZsGoGo\utils;

use think\cache\driver\Redis;

class RedisUtil {
    /**
     * @var Redis
     */
    private $redis;

    public function __construct() {
        $this->redis = new Redis(config("redis"));
    }


    /**
     * 字符串相关操作
     * @param string $key
     * @param $value
     * @param int|null $ttl
     * @return bool
     */
    public function set(string $key, $value, int $ttl = null): bool {
        return$this->redis->set($key,$value,$ttl);
    }


    public function get(string $key,string $default = null) {
        return $this->redis->get($key,$default);
    }


    public function has(string $key): bool {
        return $this->redis->has($key);
    }

    public function delete(string $key): bool {
        return $this->redis->delete($key);
    }


    public function clear(): bool {
        return $this->redis->clear();
    }


    /**
     * list 相关操作
     * @param string $key
     * @param string $value
     * @return mixed
     */
    public function lpush(string $key,string $value){
        return $this->redis->lpush($key,$value);
    }

    public function rpush(string $key,string $value){
        return $this->redis->rpush($key,$value);
    }

    public function lpop(string $key){
        return $this->redis->lpop($key);
    }

    public function rpop(string $key){
        return $this->redis->rpop($key);
    }

    public function lrange(string $key,int $start = 0,int $end = -1){
        return $this->redis->lrange($key,$start,$end);
    }


    /**
     * 无序集合set相关操作
     * @param string $key
     * @param string $value
     * @return mixed
     */
    public function sadd(string $key,string $value){
        return $this->redis->sadd($key,$value);
    }

    public function smembers(string $key) :array {
        return $this->redis->smembers($key);
    }

    public function scard(string $key){
        return $this->redis->scard($key);
    }

    public function srem(string $key,string $value){
        return $this->redis->srem($key,$value);
    }

    public function sismember(string $key,string $value) :bool {
        return $this->redis->sismember($key,$value);
    }

}