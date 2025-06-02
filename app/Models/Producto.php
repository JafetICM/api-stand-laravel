<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = ['nombre'];

    public function visitantes() {
        return $this->belongsToMany(Visitante::class, 'producto_visitante');
    }
}
