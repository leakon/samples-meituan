<?php


namespace SamplesMeituan;

/**
 *
 */

class Factory {

    // 像 Javascript 一样，顺序检查参数列表，第一个不为 empty 的参数直接返回
    public static function asap() {

        $args       = func_get_args();

        foreach ($args as $var) {
            if (isset($var) && !empty($var)) {
                return  $var;
            }
        }

        $len        = count($args);

        return  $len > 0 ? $args[$len - 1] : null;

    }

}

