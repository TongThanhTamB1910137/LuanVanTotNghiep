<?php

namespace App\Exports;

use App\Models\sale_statisticals;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class saleExport implements FromCollection, WithTitle, WithHeadings, WithStyles, ShouldAutoSize
{
    private $codeQ;
    private $dataQ;
    private $year;
    //code query 
    //1 by day
    //2 by moth

    public function __construct($c, $d,$y)
    {
        $this->codeQ = $c;
        $this->dataQ = $d;
        $this->year = $y;
    }
    public function collection()
    {

        if ($this->codeQ == 1) {
            $getData = sale_statisticals::where('ngay_ban', $this->dataQ)->get(["id_don_hang", "ngay_ban", "tien_don_hang"]);
            return $getData;
        }
        $getData = sale_statisticals::whereYear('ngay_ban', $this->year)->whereMonth('ngay_ban', $this->dataQ)->get(["id_don_hang", "ngay_ban", "tien_don_hang"]);
        return $getData;
    }

    public function getRowAndSumMoney($codeQ, $dataQ)
    {
        $row = 0;
        $sumMoney = 0;

        if ($codeQ == 1) {
            $getData = sale_statisticals::where('ngay_ban', $this->dataQ)->get(["id_don_hang", "ngay_ban", "tien_don_hang"]);

            $tempMoney = 0;
            foreach ($getData as $value) {
                $tempMoney += $value['tien_don_hang'];
            }
            $row1 = $getData->count();
            return [$row1, $tempMoney];
        }
        $getData = sale_statisticals::whereYear('ngay_ban', $this->year)->whereMonth('ngay_ban', $dataQ)->get(["id_don_hang", "ngay_ban", "tien_don_hang"]);
        $row = $getData->count();
        foreach ($getData as $v) {
            $sumMoney += $v['tien_don_hang'];
        }
        return [$row, $sumMoney];
    }

    public function title(): string
    {
        if ($this->codeQ == 1) {
            return "TIEN BAN DUOC NGAY " . $this->dataQ;
        }
        return "TIEN BAN DUOC THANG " . $this->dataQ;
    }

    public function headings(): array
    {
        # code...
        return [
            "ID ĐƠN HÀNG", "NGÀY BÁN", "TIỀN ĐƠN HÀNG"
        ];
    }

    public function styles(Worksheet $sheet)
    {

        $sheet->getStyle(1)->getFont()->setBold(true);
        $sheet->getStyle('A:D')->getAlignment()->setHorizontal('center');
        $arrayD = $this->getRowAndSumMoney($this->codeQ, $this->dataQ);
        $getRow = $arrayD[0];
        $getMoney = $arrayD[1];
        $sheet->setCellValue('A' . $getRow + 2, 'TIỀN BÁN HÀNG: ' . $getMoney . " VND");
        $sheet->getStyle($getRow + 2)->getFont()->setBold(true);
        $numR = $getRow + 2;
        $sheet->mergeCells('A' . $numR . ':' . 'C' . $numR);
        $sheet->getStyle('A' . $numR . ':' . 'C' . $numR)->getAlignment()->setHorizontal('right');
    }
}
