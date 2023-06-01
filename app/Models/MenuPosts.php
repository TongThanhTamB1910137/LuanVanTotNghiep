<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuPosts extends Model
{
    use HasFactory;
    protected $table = "menu_posts";
    protected $fillable = [
        'trangthai', 'tendanhmuc', 'mota', 'trangthai', 'slug', 'id'
    ];
}