<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory;
    protected $table = 'ward';
    protected $fillable = ['district_code','ward_code','ward_name','ward_type'];
    public function District(){
        return $this->belongsTo(District::class,'district_code');
    }

    public function FeeShip() {
        return $this->hasOne(FeeShip::class, 'ward_id', 'ward_code');
    }
}