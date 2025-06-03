<?php
// app/Models/Visitante.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

 /**
  * @OA\Schema(
  *     schema="Visitante",
  *     type="object",
  *     title="Visitante",
  *     required={"id", "nombre_completo", "correo", "telefono"},
  *     @OA\Property(property="id", type="integer", example=1),
  *     @OA\Property(property="nombre_completo", type="string", example="Juan Pérez"),
  *     @OA\Property(property="correo", type="string", format="email", example="juan.perez@example.com"),
  *     @OA\Property(property="telefono", type="string", example="+521234567890"),
  *     @OA\Property(property="empresa", type="string", example="Empresa S.A."),
  *     @OA\Property(property="cargo", type="string", example="Gerente de ventas"),
  *     @OA\Property(property="fecha_registro", type="string", format="date-time", example="2025-06-02T15:30:00Z"),
  *     @OA\Property(property="estado", type="string", example="Nuevo"),
  *     @OA\Property(property="notas", type="string", example="Cliente interesado en producto X"),
  * )
  */
class Visitante extends Model
{
    protected $fillable = [
        'nombre_completo',
        'correo',
        'telefono',
        'empresa',
        'cargo',
        'fecha_registro',
        'estado',
        'notas',
    ];

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'producto_visitante', 'visitante_id', 'producto_id');
    }
}
