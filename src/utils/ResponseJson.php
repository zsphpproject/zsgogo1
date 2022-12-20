<?php

namespace ZsGoGo\utils;

use app\common\ErrorNums;
use think\response\Json;

class ResponseJson {
    /**
     * @param $data
     * @param string $msg
     * @return Json
     */
    public static function success($data = null, string $msg = "success"): Json {
        $data = [
            "code" => ErrorNums::SUCCESS,
            "msg" => $msg,
            "data" => $data,
            "request_id" => SnowFlakeUtil::getInstance()->getCurrentId()
        ];
        return json($data);
    }


    /**
     * @param int $code
     * @param string $msg
     * @param $data
     * @return Json
     */
    public static function fail(int $code = ErrorNums::SYS_ERROR,string $msg = "",$data = null): Json {
        $data = [
            "code" => $code,
            "msg" => $msg ?: ErrorNums::getInstance()->getMessage($code),
            "data" => $data,
            "request_id" => ""
        ];
        return json($data);
    }
}