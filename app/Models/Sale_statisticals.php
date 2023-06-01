<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale_statisticals extends Model
{
    use HasFactory;
    protected $table='sale_statisticals';
    protected $fillable=[
        'trang_thai','tien_don_hang','id_don_hang','id','ngay_ban'
    ];
}
