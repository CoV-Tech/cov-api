<?php namespace cov;

spl_autoload_register(
    function ($pClassName) {
        $arr = explode("\\", $pClassName);
        $am = count(explode("\\", __NAMESPACE__));
        if ($arr[0] !== "cov"){
            return;
        }
        $str = __DIR__;
        for ($i = 0; $i < $am; $i++){
            $str .= DIRECTORY_SEPARATOR."..";
        }
        include_once($str.DIRECTORY_SEPARATOR. $pClassName.".php");
    }
);