<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Visitante;
use App\Models\Producto;
use Illuminate\Support\Facades\Validator;

class VisitanteController extends Controller
{
    public function index()
    {
        return response()->json(Visitante::with(['productos'])->get(), 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_completo' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'telefono' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $visitante = Visitante::create($request->all());

        if ($request->has('productos')) {
            $visitante->productos()->sync($request->productos);
        }

        return response()->json($visitante->load('productos'), 201);
    }

    public function show($id)
    {
        $visitante = Visitante::with('productos')->find($id);
        if (!$visitante) {
            return response()->json(['message' => 'Visitante no encontrado'], 404);
        }
        return response()->json($visitante);
    }

    public function update(Request $request, $id)
    {
        $visitante = Visitante::find($id);
        if (!$visitante) {
            return response()->json(['message' => 'Visitante no encontrado'], 404);
        }

        $visitante->update($request->all());

        if ($request->has('productos')) {
            $visitante->productos()->sync($request->productos);
        }

        return response()->json($visitante->load('productos'));
    }

    public function destroy($id)
    {
        $visitante = Visitante::find($id);
        if (!$visitante) {
            return response()->json(['message' => 'Visitante no encontrado'], 404);
        }
        $visitante->delete();
        return response()->json(['message' => 'Visitante eliminado']);
    }

    public function asignarProductos(Request $request)
    {
        $request->validate([
            'visitante_id' => 'required|exists:visitantes,id',
            'productos' => 'required|array',
            'productos.*' => 'exists:productos,id',
        ]);

        $visitante = Visitante::find($request->visitante_id);
        $visitante->productos()->sync($request->productos);

        return response()->json(['message' => 'Productos asignados correctamente']);
    }

    public function productos($visitante_id)
    {
        $visitante = Visitante::with('productos')->find($visitante_id);
        if (!$visitante) {
            return response()->json(['message' => 'Visitante no encontrado'], 404);
        }
        return response()->json($visitante->productos);
    }

    public function visitantesPorProducto($producto_id)
    {
        $producto = Producto::find($producto_id);
        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        return response()->json($producto->visitantes);
    }

    public function exportar()
    {
        $visitantes = \App\Models\Visitante::with('productos')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="visitantes.csv"',
        ];

        $callback = function () use ($visitantes) {
            $handle = fopen('php://output', 'w');

            // Encabezados
            fputcsv($handle, [
                'ID',
                'Nombre Completo',
                'Correo',
                'Teléfono',
                'Empresa',
                'Cargo',
                'Fecha Registro',
                'Estado',
                'Notas',
                'Productos de Interés'
            ]);

            // Datos
            foreach ($visitantes as $v) {
                fputcsv($handle, [
                    $v->id,
                    $v->nombre_completo,
                    $v->correo,
                    $v->telefono,
                    $v->empresa,
                    $v->cargo,
                    $v->fecha_registro,
                    $v->estado,
                    $v->notas,
                    $v->productos->pluck('nombre')->implode(', ')
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
