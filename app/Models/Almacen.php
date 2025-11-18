<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    public function productos(){
        return $this->belongsToMany(Producto::class)
                    ->withTimestamps()
                    ->withPivot(["cantidad_actual","fecha_actualizacion"]);
    }

    public function sucursal(){
        return $this->belongsTo(Sucursal::class);
    }
}
