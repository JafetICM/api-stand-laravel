<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seguimiento;
use Illuminate\Support\Facades\Validator;

class SeguimientoController extends Controller
{
    public function index()
    {
        return response()->json(Seguimiento::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'visitante_id' => 'required|exists:visitantes,id',
            'tipo' => 'required|in:Llamada,Email,Cita,Comentario',
            'descripcion' => 'nullable|string',
            'realizado_por' => 'required|integer|exists:usuarios,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $seguimiento = Seguimiento::create($request->all());
        return response()->json($seguimiento, 201);
    }

    public function show($id)
    {
        $seguimiento = Seguimiento::find($id);
        if (!$seguimiento) {
            return response()->json(['message' => 'Seguimiento no encontrado'], 404);
        }

        return response()->json($seguimiento);
    }

    public function update(Request $request, $id)
    {
        $seguimiento = Seguimiento::find($id);
        if (!$seguimiento) {
            return response()->json(['message' => 'Seguimiento no encontrado'], 404);
        }

        $seguimiento->update($request->all());
        return response()->json($seguimiento);
    }

    public function destroy($id)
    {
        $seguimiento = Seguimiento::find($id);
        if (!$seguimiento) {
            return response()->json(['message' => 'Seguimiento no encontrado'], 404);
        }

        $seguimiento->delete();
        return response()->json(['message' => 'Seguimiento eliminado']);
    }

    public function porVisitante($visitante_id)
    {
        return response()->json(Seguimiento::where('visitante_id', $visitante_id)->get());
    }
}
