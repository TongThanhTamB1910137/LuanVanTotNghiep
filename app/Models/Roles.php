<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory;
    protected $tabele='roles';
    protected $fillable = [
        'id','permission','status','type_acc'
    ]; //

}
