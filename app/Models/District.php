<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $table = 'district';
    protected $fillable = ['district_code', 'district_name', 'district_type', 'province_code'];
    public function Province()
    {
        return $this->belongsTo(Province::class, 'district_code');
    }

    public function FeeShip()
    {
        return $this->hasOne(FeeShip::class, 'district_id', 'district_code');
    }

    public function Ward()
    {
        return $this->hasMany(Ward::class, 'district_code', 'district_code');
    }

    public function ProvinceFee()
    {
        return $this->hasOne(FeeShip::class, 'province_id', 'province_code');
    }

    public function getProvince()
    {
        return $this->belongsTo(Province::class, 'province_code', 'province_code');
    }


    // lay xa 

}