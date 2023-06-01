<?php

namespace App\Exports;

use App\Models\ManagerMaterialUse;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;

class buyMaterialExport implements FromCollection, WithTitle, WithHeadings, WithStyles, ShouldAutoSize
{
    private $codeQuery;
    private $getDate;
    private $year;
    public $sumMoneyBuy = 0;
    //code query 
    //1 by day
    //2 by moth

    function __construct($code, $data,$year)
    {
        $this->codeQuery = $code;
        $this->getDate = $data;
        $this->year=$year;
    }
    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {
        if ($this->codeQuery == 1) {
            $getData = ManagerMaterialUse::where('ngay_tong_ket', $this->getDate)->get(["id_nguyen_lieu", "so_luong", "don_gia", "ngay_tong_ket"]);
            foreach ($getData as $key => $value) {
                $getData[$key]->id_nguyen_lieu = $value->Name->ten_nglieu;
            }
            return $getData;
        }
        $nowYear = Carbon::now()->year;
        $getData = ManagerMaterialUse::whereYear('ngay_tong_ket', $nowYear)->whereMonth('ngay_tong_ket', $this->getDate)->get(["id_nguyen_lieu", "so_luong", "don_gia", "ngay_tong_ket"]);
        foreach ($getData as $key => $value) {
            $getData[$key]->id_nguyen_lieu = $value->Name->ten_nglieu;
        }

        $getData = ManagerMaterialUse::whereYear('ngay_tong_ket', $this->year)->whereMonth('ngay_tong_ket', $this->getDate)->get(["id_nguyen_lieu", "so_luong", "don_gia", "ngay_tong_ket"]);
        return $getData;
    }

    public function getRowAndSumMoney($codeQ, $dataQ)
    {
        $row = 0;
        $sumMoney = 0;

        if ($codeQ == 1) {
            $getData = ManagerMaterialUse::where('ngay_tong_ket', $this->getDate)->get(["id_nguyen_lieu", "so_luong", "don_gia", "ngay_tong_ket"]);

            $money = 0;
            foreach ($getData as $v) {
                $money += $v['so_luong'] * $v['don_gia'];
            }
            $row1 = $getData->count();
            return [$row1, $money];
        }
        $getData = ManagerMaterialUse::whereYear('ngay_tong_ket', $this->year)->whereMonth('ngay_tong_ket', $this->getDate)->get(["id_nguyen_lieu", "so_luong", "don_gia", "ngay_tong_ket"]);
        $row = $getData->count();
        foreach ($getData as $v) {
            $sumMoney = $v['don_gia'] * $v['so_luong'];
        }
        return [$row, $sumMoney];
    }

    public function title(): string
    {

        if ($this->codeQuery == 1) {
            return "MUA NGUYÊN LIỆU NGÀY" . $this->getDate;
        }
        return "MUA NGUYÊN LIỆU THÁNG " . $this->getDate;
    }
    public function headings(): array
    {
        # code...
        return [
            "ID NGUYÊN LIỆU", "SỐ LƯỢNG", "ĐƠN GIÁ", "NGÀY TỔNG KẾT"
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $getR = $this->getRowAndSumMoney($this->codeQuery, $this->getDate);
        $getRow = $getR[0];
        $getMoney = $getR[1];
        $numR = $getRow + 2;

        $sheet->getStyle(1)->getFont()->setBold(true);
        $sheet->setCellValue('A' . $numR, ' TIỀN MUA NGUYÊN LIỆU: ' . $getMoney . " VND");
        $sheet->getStyle('A' . $numR)->getFont()->setBold(true);
        $sheet->getStyle('A:D')->getAlignment()->setHorizontal('center');
        $sheet->mergeCells('A' . $numR . ':' . 'D' . $numR);
        $sheet->getStyle('A' . $numR . ':' . 'D' . $numR)->getAlignment()->setHorizontal('right');
    }
}