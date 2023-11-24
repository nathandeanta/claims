<?php

namespace App\Helper;

use DateTime;

class ConvertDatesTime
{
    static function dateUpdate($dateString)
    {
        $date = DateTime::createFromFormat("d/m/Y", $dateString);
        if ($date !== false) {
            $formattedDate = $date->format("Y-m-d");
            return $formattedDate;
        }

        return false;

    }

}