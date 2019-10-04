<?php

/*
 */

namespace SamplesMeituan\Kernel\Logs\Monolog\Formatter;

use SamplesMeituan\Kernel\BaseAccessToken;

use Monolog\Formatter\LineFormatter;

/**
 *
 */
class PrintR extends LineFormatter
{

    protected function replaceNewlines(string $str): string
    {
        return  $str;
    }

    protected function convertToString($data): string
    {

        if (null === $data || is_bool($data)) {
            return var_export($data, true);
        }

        if (is_scalar($data)) {
            return (string) $data;
        }

        if (is_array($data) && count($data)) {

            $outputs    = [];

            foreach ($data as $key => $val) {
                $outputs[]  = sprintf("[%s]:\t%s", $key, $this->mixedToHumanString($val));
            }

            $str        = implode("\n", $outputs);

            return  "\n" . $str . "\n";

        }

        return (string) "\n" . $this->toJson($data, true) . "\n";

    }

    protected function mixedToHumanString($val) {

        if (is_array($val)) {
            return  print_r($val, true);
        }

        if (is_string($val) && strlen($val) >= 2) {

            $first      = substr($val, 0, 1);

            if ('[' == $first || '{' == $first) {
                $arr        = json_decode($val, true);
                return      print_r($arr, true);
            }

        }

        return  $val;

    }

}
