<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizePros extends Model
{
    use HasFactory;

    protected $table='size_pros';
    protected $fillable=[
        'id','id_pro','id_size'
    ];

    public function sanpham()
    {
        return $this->belongsToMany(Products::class, 'size_pros', 'id_size', 'id_pro');
    }
}
