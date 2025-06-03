<?php
// app/Models/Producto.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

 /**
  * @OA\Schema(
  *     schema="Producto",
  *     type="object",
  *     title="Producto",
  *     required={"id", "nombre"},
  *     @OA\Property(property="id", type="integer", example=1),
  *     @OA\Property(property="nombre", type="string", example="Producto A")
  * )
  */
class Producto extends Model
{
    protected $fillable = [
        'nombre',

    ];

    public function visitantes()
    {
        return $this->belongsToMany(Visitante::class, 'producto_visitante', 'producto_id', 'visitante_id');
    }
}
