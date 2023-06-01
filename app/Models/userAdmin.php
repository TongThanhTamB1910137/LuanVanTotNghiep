<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userAdmin extends Model
{
    use HasFactory;
    protected $table="user_admins";
    protected $fillable=[
        'email','name_staff','roles_id','password'
    ];
    public function roles()
    {
        return $this->belongsTo(Role::class);
    }
    public function hasRole($role)
    {
        return null !== $this->roles()->where('type_acc', $role)->first;
    }

}
