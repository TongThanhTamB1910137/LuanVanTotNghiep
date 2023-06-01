<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'payments';
    protected $fillable = ['tongtien', 'mancc','loaithanhtoan', 'sohoadon','magiaodich', 'magiaodichBank', 'noidung', 'ngaythanhtoan', 'id_donhang'];

    public function Donhang() {
       return $this->belongsTo(Order::class, 'id_donhang');
    }

}
