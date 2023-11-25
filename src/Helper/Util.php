<?php

namespace App\Helper;

class Util
{
    static function validMd5($hash)
    {
        $pattern = '/^[a-f0-9]{32}$/';

        if (preg_match($pattern, $hash)) {
            return true;
        }

        return false;
    }

}