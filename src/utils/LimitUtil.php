<?php

namespace ZsGoGo\utils;

use app\common\ErrorNums;
use app\common\exception\AppException;
use think\facade\Cache;


class LimitUtil {

    private static $Instance;

    protected float $rate = 1;  // 每秒1个
    protected int $tokens = 1;   // 最多1个
    protected int $expireTime = 60;  // 缓存过期时间

    private function __construct(array $config = []) {
        if (!empty($config)){
            $this->rate = $config["rage"];
            $this->tokens = $config["tokens"];
            $this->expireTime = $config["express_time"];
        }
    }

    public function filter(string $key) : bool{
        $now = time();
        $lastData = $this->getLastData($key);  // 上次请求的数据

        // 上次请求的时间
        $lastTime = $lastData ? $lastData["time"] : $now;

        // 两次请求间隔的时间
        $intervalTime = $now - $lastTime;

        // 两次间隔请求增加的令牌数量(本次新发的数量)
        $addTokens = $intervalTime * $this->rate;

        // 上次请求剩余的令牌数量
        $lastTokens = $lastData ? $lastData["tokens"] : $this->tokens;

        // 剩余的令牌数量 + 本次新发的令牌数量 保存起来
        $totalTokens = min($this->tokens,$addTokens + $lastTokens);

        // 减掉本次消耗的一个令牌，剩余的令牌
        $surplusTokens = $totalTokens - 1;

        if ($surplusTokens < 0 && $lastData) throw new AppException(ErrorNums::FREQUENT_REQUEST);

        $data = ["time" => $now,"tokens" => $surplusTokens];
        Cache::set($key,json_encode($data),$this->expireTime);
        return true;
    }

    private function getLastData(string $key) {
        $data = Cache::get($key);
        if ($data){
            return json_decode($data, true);
        }
        return null;
    }


    public static function getInstance(array $config = []): LimitUtil {
        if (self::$Instance instanceof LimitUtil) return self::$Instance;
        return self::$Instance = new self($config);
    }

}