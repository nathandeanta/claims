<?php

namespace App\Service;

use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelService
{

    public function readfile($path): array
    {
        $spreadsheet = IOFactory::load($path);

        $worksheet = $spreadsheet->getActiveSheet();

        $data = [];

        foreach ($worksheet->getRowIterator() as $row) {
            $line = [];

            foreach ($row->getCellIterator() as $cell) {
                $cellValue = $cell->getValue();
                $line[] = $cellValue;
            }

            $data[] = $line;
        }

        return $data;
    }

    public function isNegativeNumber($str) {

        if (strpos($str, '-') === 0) {

            $str = substr($str, 1);

            return is_numeric($str);
        }

        return false;
    }

}