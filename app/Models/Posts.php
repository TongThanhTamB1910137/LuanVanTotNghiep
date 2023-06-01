<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    use HasFactory;
    protected $table = 'posts';
    protected $fillable = ['tieude', 'slug', 'hinhanh', 'noidung', 'mota', 'trangthai', 'loaibaiviet', 'hot', 'id_danhmuc'];
    public function Danhmuc()
    {
        return $this->belongsTo(MenuPosts::class, 'id_danhmuc');
    }
}