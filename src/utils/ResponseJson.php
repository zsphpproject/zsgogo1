<?php

namespace ZsGoGo\utils;

use app\common\ErrorNums;
use think\Response;
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
            "request_id" => SnowFlakeUtil::getInstance()->getCurrentId(),
            "data" => $data
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
            "request_id" => SnowFlakeUtil::getInstance()->getCurrentId(),
            "data" => $data
        ];
        return json($data);
    }


    /**
     * @param int $code
     * @param string $msg
     * @param int $statusCode
     * @return Response
     */
    public static function error(int $code = ErrorNums::SYS_ERROR, string $msg = "", int $statusCode = 400): Response {
        return Response::create([
            "code" => ErrorNums::Unauthorized,
            "msg" => $msg,
            "request_id" => SnowFlakeUtil::getInstance()->getCurrentId(),
            "data" => null
        ],"json",$statusCode);
    }
}