<?php

namespace ZsGoGo\utils;

use Godruoyi\Snowflake\Snowflake;
use think\facade\Env;

class SnowFlakeUtil {

    private static $_instance;
    protected $datacenterId = 0;

    protected $workId = 0;

    protected $currentId = "";

    public function createId(): string {
        $snowflake = new Snowflake($this->datacenterId,$this->workId);
        $id = $snowflake->id();
        $this->currentId = Date("Ymd").$id;
        return $id;
    }

    public function setCurrentId(string $currentId){
        $this->currentId = $currentId;
    }

    public function getCurrentId() :string {
        return $this->currentId;
    }

    public static function getInstance(): SnowFlakeUtil {
        if (!(self::$_instance instanceof SnowFlakeUtil)) {
            $param = Env::get('work_id', 1);
            self::$_instance = new self($param);
        }
        return self::$_instance;
    }
}