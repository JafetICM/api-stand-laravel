<?php
//routes\api.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VisitanteController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\SeguimientoController;
use App\Http\Controllers\Api\UsuarioController;

// Rutas Visitantes
Route::apiResource('visitantes', VisitanteController::class);
Route::post('visitantes/asignar-productos', [VisitanteController::class, 'asignarProductos']);
Route::get('visitantes/{visitante_id}/productos', [VisitanteController::class, 'productos']);
Route::get('visitantes/producto/{producto_id}', [VisitanteController::class, 'visitantesPorProducto']);
Route::get('visitantes/exportar', [VisitanteController::class, 'exportar']);

// Rutas Productos
Route::apiResource('productos', ProductoController::class);

// Rutas Seguimientos
Route::get('seguimientos/{visitante_id}', [SeguimientoController::class, 'index']);
Route::post('seguimientos', [SeguimientoController::class, 'store']);
Route::put('seguimientos/{id}', [SeguimientoController::class, 'update']);
Route::delete('seguimientos/{id}', [SeguimientoController::class, 'destroy']);

// Rutas Usuarios
Route::apiResource('usuarios', UsuarioController::class);
