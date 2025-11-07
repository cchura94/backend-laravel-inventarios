<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    public function almacenes(){
        return $this->hasMany(Almacen::class);
    }

    public function users(){
        return $this->belongsToMany(User::class, "sucursal_usuario", "sucursal_id", "usuario_id");
    }
}
