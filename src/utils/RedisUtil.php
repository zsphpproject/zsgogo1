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
}