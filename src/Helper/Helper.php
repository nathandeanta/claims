<?php

namespace App\Helper;

class Helper
{
    static function cleanCnpjAndCpf($document)
    {
        return ( preg_replace('/\D/', '', $document));
    }

    static function generateUniqueRandomNumbers($min, $max, $count) {

        if ($count > ($max - $min + 1)) {
            return false;
        }

        $numbers = range($min, $max);

        shuffle($numbers);

        return array_slice($numbers, 0, $count);
    }

}