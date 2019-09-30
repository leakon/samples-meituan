<?php


namespace SamplesMeituan\Common;

/**
 *
 */

class Signature {

    public static function getVerifySign($data, $secret) {
        $string     = self::formatParameters($data, false);
        $before     = $secret . $string . $secret;
        $ret        = md5($before);
        return      $ret;
    }

    public static function formatParameters($paraMap, $urlencode = false) {
        ksort($paraMap);
        $arrs   = [];
        foreach ($paraMap as $k => $v) {
            if ($k == "sign") {
                continue;
            }
            $v  = trim($v);
            // 有的参数要求值为空，因此本条件要去掉
            if (is_null($v) || strlen($v) < 1) {
                continue;
            }
            if ($urlencode) {
                $v = urlencode($v);
            }
            $arrs[]     = $k . '' . $v;
        }
        $reqPar     = implode('', $arrs);
        return $reqPar;
    }

}

