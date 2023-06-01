<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;
    protected $table = "wishlists";
    public $timestamps = false;
    protected $fillable = [
        'id_sanpham', 'id_khachhang'
    ];
}