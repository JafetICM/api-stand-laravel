<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $fillable = ['nombre', 'correo', 'rol'];

    public function visitantes() {
        return $this->hasMany(Visitante::class);
    }
}
