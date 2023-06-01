<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $table = 'order_details';
    protected $fillable = ['id_donhang', 'id_sanpham', 'id_size', 'soluong', 'giaban', 'giagoc'];

    public function product()
    {
        return $this->belongsTo(Products::class, 'id_sanpham');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'id_donhang');
    }
    public function size()
    {
        return $this->belongsTo(Sizes::class, 'id_size');
    }

    public function getCoupon()
    {
        return $this->belongsTo(Coupon::class, 'giagoc');
    }
}