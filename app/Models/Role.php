<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = [
        'name'
    ];
    //1 role memiliki banyak user 
    public function user() {
        return $this->hasMany(User::class,'role_id');
    }
    //  function ini saya salah simpan dikarenakan tidak fokus
    // public function role() {
    //     return $this->belongsTo(Role::class,'role_id');
    // }
}
