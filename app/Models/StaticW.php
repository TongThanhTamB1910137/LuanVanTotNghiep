<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaticW extends Model
{
    use HasFactory;
    protected $table = 'static';
    protected $fillable = [
        'id', 'tieude', 'mota', 'noidung', 'loai', 'trangthai',
    ];
}