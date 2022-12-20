<?php
declare(strict_types=1);



function p(array $arr){
    echo "<pre>";
    print_r($arr);
}


function md5s(string $str): string {
    return md5(md5($str));
}


