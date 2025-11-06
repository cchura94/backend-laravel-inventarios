<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    // categorias
    // protected $table = "categorias";

    // protected $primaryKey = 'id';
    // public $incrementing = false;
    // protected $keyType = 'string';

    // public $timestamps = false;

    public function productos(){
        return $this->hasMany(Producto::class);
    }


}
