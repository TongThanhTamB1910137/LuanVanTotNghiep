<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_Coupon extends Model
{
    use HasFactory;
    protected $table = "products_coupon";
    public $timestamps = FALSE;
    protected $fillable = [
        'id_product', 'id_coupon'
    ];

    public function Coupon()
    {
        return $this->belongsTo(Coupon::class, 'id_coupon');
    }

    public function getProduct()
    {
        return $this->belongsTo(Products::class, 'id_product');
    }
}