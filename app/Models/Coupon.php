<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $table = "coupon";
    protected $fillable = [
        'id', 'trangthai', 'mota', 'code', 'hinhanh', 'ten', 'ngaybd', 'ngaykt', 'giamgia', 'dieukien', 'loaigiam', 'hienthi'
    ];

    public function Product()
    {
        return $this->belongsToMany(Products::class, 'products_coupon', 'id_coupon', 'id_product');
    }
}