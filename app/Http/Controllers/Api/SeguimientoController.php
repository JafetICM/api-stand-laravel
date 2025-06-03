<?php
//app/Http\Controllers\Api/SeguimientoController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seguimiento;
use Illuminate\Support\Facades\Validator;

class SeguimientoController extends Controller
{
    
    public function index($visitante_id)
    {
        $seguimientos = Seguimiento::where('visitante_id', $visitante_id)->get();
        return response()->json($seguimientos);
    }

    /**
     * @OA\Post(
     *     path="/api/seguimientos",
     *     summary="Registrar un nuevo seguimiento",
     *     tags={"Seguimientos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"visitante_id", "tipo", "comentarios"},
     *             @OA\Property(property="visitante_id", type="integer"),
     *             @OA\Property(property="tipo", type="string", description="Tipo de seguimiento"),
     *             @OA\Property(property="comentarios", type="string", description="Comentarios"),
     *             @OA\Property(property="fecha", type="string", format="date", description="Fecha del seguimiento")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Seguimiento creado"),
     *     @OA\Response(response=422, description="Validación fallida")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'visitante_id' => 'required|exists:visitantes,id',
            'tipo' => 'required|string',
            'comentarios' => 'required|string',
            'fecha' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $seguimiento = Seguimiento::create($request->all());

        return response()->json($seguimiento, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/seguimientos/{id}",
     *     summary="Actualizar un seguimiento",
     *     tags={"Seguimientos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del seguimiento",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="tipo", type="string"),
     *             @OA\Property(property="comentarios", type="string"),
     *             @OA\Property(property="fecha", type="string", format="date")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Seguimiento actualizado"),
     *     @OA\Response(response=404, description="Seguimiento no encontrado")
     * )
     */
    public function update(Request $request, $id)
    {
        $seguimiento = Seguimiento::find($id);
        if (!$seguimiento) {
            return response()->json(['message' => 'Seguimiento no encontrado'], 404);
        }

        $seguimiento->update($request->all());

        return response()->json($seguimiento);
    }

    /**
     * @OA\Delete(
     *     path="/api/seguimientos/{id}",
     *     summary="Eliminar un seguimiento",
     *     tags={"Seguimientos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del seguimiento",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Seguimiento eliminado"),
     *     @OA\Response(response=404, description="Seguimiento no encontrado")
     * )
     */
    public function destroy($id)
    {
        $seguimiento = Seguimiento::find($id);
        if (!$seguimiento) {
            return response()->json(['message' => 'Seguimiento no encontrado'], 404);
        }
        $seguimiento->delete();
        return response()->json(['message' => 'Seguimiento eliminado']);
    }
}
