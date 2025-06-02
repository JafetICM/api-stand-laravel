<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seguimiento extends Model
{
    protected $fillable = ['visitante_id', 'tipo', 'descripcion', 'fecha', 'realizado_por'];

    public function visitante() {
        return $this->belongsTo(Visitante::class);
    }

    public function usuario() {
        return $this->belongsTo(Usuario::class, 'realizado_por');
    }
}

