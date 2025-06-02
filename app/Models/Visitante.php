<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitante extends Model
{
    protected $fillable = [
        'nombre_completo', 'correo', 'telefono', 'empresa', 'cargo', 'fecha_registro', 'estado', 'notas', 'usuario_id'
    ];

    public function seguimientos() {
        return $this->hasMany(Seguimiento::class);
    }

    public function productos() {
        return $this->belongsToMany(Producto::class, 'producto_visitante');
    }

    public function usuario() {
        return $this->belongsTo(Usuario::class);
    }
}
