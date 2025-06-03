<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/Seguimiento.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Seguimiento",
 *     type="object",
 *     title="Seguimiento",
 *     required={"id", "visitante_id", "tipo", "comentarios"},
 *     @OA\Property(property="id", type="integer", format="int64"),
 *     @OA\Property(property="visitante_id", type="integer", format="int64", description="ID del visitante asociado"),
 *     @OA\Property(property="tipo", type="string", description="Tipo de seguimiento"),
 *     @OA\Property(property="comentarios", type="string", description="Comentarios del seguimiento"),
 *     @OA\Property(property="fecha", type="string", format="date", description="Fecha del seguimiento"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Seguimiento extends Model
{
    protected $fillable = ['visitante_id', 'tipo', 'comentarios', 'fecha'];

    public function visitante()
    {
        return $this->belongsTo(Visitante::class);
    }
}
