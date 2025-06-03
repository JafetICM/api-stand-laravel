<?php
// app/Models/Usuario.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Usuario",
 *     type="object",
 *     required={"id", "nombre", "correo"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nombre", type="string", example="Juan Pérez"),
 *     @OA\Property(property="correo", type="string", format="email", example="juan@example.com")
 * )
 */
class Usuario extends Model
{
    protected $fillable = ['nombre', 'correo', 'rol'];

    public function visitantes()
    {
        return $this->hasMany(Visitante::class);
    }
}
