<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_statisticals extends Model
{
    use HasFactory;
    protected $table='order_statisticals';
    protected $fillable=[
        'id','ten_san_pham_order','so_luot_dat','trang_thai'
    ];
}
