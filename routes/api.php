<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VisitanteController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\SeguimientoController;
use App\Http\Controllers\Api\UsuarioController;

Route::middleware('api')->group(function () {
    // Visitantes
    Route::apiResource('visitantes', VisitanteController::class);
    Route::get('visitantes/exportar', [VisitanteController::class, 'exportar']);
    Route::post('visitantes/asignar-productos', [VisitanteController::class, 'asignarProductos']);
    Route::get('visitantes/{visitante_id}/productos', [VisitanteController::class, 'productos']);
    Route::get('visitantes/producto/{producto_id}', [VisitanteController::class, 'visitantesPorProducto']);

    // Productos
    Route::apiResource('productos', ProductoController::class);
    

    // Seguimientos
    Route::apiResource('seguimientos', SeguimientoController::class);
    Route::get('seguimientos/{visitante_id}', [SeguimientoController::class, 'porVisitante']);

    // Usuarios
    Route::apiResource('usuarios', UsuarioController::class);



});
