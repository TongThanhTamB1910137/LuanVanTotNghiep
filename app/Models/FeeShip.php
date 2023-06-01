<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeShip extends Model
{
    use HasFactory;
    protected $table = 'feeship';
    protected $fillable = ['province_id', 'district_id', 'ward_id', 'feeship', 'trangthai', 'parent'];

    public function Province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'province_code');
    }


    public function District()
    {
        return $this->hasMany(District::class, 'province_code', 'province_id');
    }

    public function GetProvince()
    {
        return $this->hasOne(Province::class, 'province_code', 'province_id');
    }


    public function GetDistrict()
    {
        return $this->hasOne(District::class, 'province_code', 'province_id');
    }

    public function GetWard()
    {
        return $this->hasOne(Ward::class, 'ward_code', 'ward_id');
    }

    // public function District() {
    //     return $this->hasMany(FeeShip::class,'province_id');
    // }
}