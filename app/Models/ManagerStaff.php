<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagerStaff extends Model
{
    use HasFactory;
    protected $table ="manager_staff";
    protected $fillable = [
        'status','roles','password','email','name_staff'
    ];
}
