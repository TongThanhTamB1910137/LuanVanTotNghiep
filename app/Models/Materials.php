<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materials extends Model
{
    use HasFactory;

    protected $table = "materials";
    protected $fillable = [
        'id', 'trang_thai', 'ngay_het_han', 'ngay_nhap', 'don_vi_nglieu', 'so_luong', 'hinh_anh', 'gia_nhap', 'ten_nglieu', 'slug'
    ];
}