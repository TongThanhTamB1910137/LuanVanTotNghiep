<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class multipleExport implements WithMultipleSheets
{

    private $code;
    private $data;
    private $year;
    public function __construct($code, $data,$year)
    {
        $this->code = $code;
        $this->data = $data;
        $this->year = $year;
    }
    public function sheets(): array
    {
        $codeQuery = $this->code;
        $dataQuery = $this->data;
        $sheets = [
            new saleExport($codeQuery, $dataQuery,$this->year),
            new buyMaterialExport($codeQuery, $dataQuery,$this->year)
        ];
        return $sheets;
    }
    public function array(): array
    {
        return $this->sheets;
    }
}
