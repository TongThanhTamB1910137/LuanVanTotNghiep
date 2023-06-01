<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class type_account extends Model
{
    use HasFactory;
    protected $table = "type_accounts";
    protected $fillable = [
        'type_account', 'trang_thai', 'id'
    ];
}
