<?php

namespace App\Helper;

class Helper
{
    static function cleanCnpjAndCpf($document)
    {
        return ( preg_replace('/\D/', '', $document));
    }

}