<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagerMaterialUse extends Model
{
    use HasFactory;
    protected $table = "manager_material_uses";
    protected $fillable = [
        'ngay_tong_ket', 'don_gia', 'so_luong', 'trang_thai', 'slug_name_mal', 'id_nguyen_lieu'
    ];
    public function Name()
    {
        return $this->belongsTo(Materials::class, 'id_nguyen_lieu');
    }
}