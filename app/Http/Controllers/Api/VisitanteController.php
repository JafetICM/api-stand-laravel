<?php
// app/Http/Controllers/Api/VisitanteController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Visitante;
use App\Models\Producto;
use Illuminate\Support\Facades\Validator;

class VisitanteController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/visitantes",
     *     summary="Obtener todos los visitantes",
     *     tags={"Visitantes"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de visitantes",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Visitante"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Visitante::with(['productos'])->get(), 200);
    }

    /**
     * @OA\Post(
     *     path="/api/visitantes",
     *     summary="Crear un nuevo visitante",
     *     tags={"Visitantes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="nombre_completo",
     *                     type="string",
     *                     description="Nombre completo del visitante"
     *                 ),
     *                 @OA\Property(
     *                     property="correo",
     *                     type="string",
     *                     description="Correo electrónico del visitante"
     *                 ),
     *                 @OA\Property(
     *                     property="telefono",
     *                     type="string",
     *                     description="Teléfono del visitante"
     *                 ),
     *                 @OA\Property(
     *                     property="productos",
     *                     type="array",
     *                     description="Productos asociados al visitante",
     *                     @OA\Items(type="integer", description="ID del producto")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Visitante creado"),
     *     @OA\Response(response=422, description="Validación de datos fallida")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/visitantes/{id}",
     *     summary="Obtener un visitante por ID",
     *     tags={"Visitantes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del visitante",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Información del visitante",
     *         @OA\JsonContent(ref="#/components/schemas/Visitante")
     *     ),
     *     @OA\Response(response=404, description="Visitante no encontrado")
     * )
     */
    public function show($id)
    {
        $visitante = Visitante::with('productos')->find($id);
        if (!$visitante) {
            return response()->json(['message' => 'Visitante no encontrado'], 404);
        }
        return response()->json($visitante);
    }

    /**
     * @OA\Put(
     *     path="/api/visitantes/{id}",
     *     summary="Actualizar un visitante",
     *     tags={"Visitantes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del visitante",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="nombre_completo", type="string"),
     *                 @OA\Property(property="correo", type="string"),
     *                 @OA\Property(property="telefono", type="string"),
     *                 @OA\Property(
     *                     property="productos",
     *                     type="array",
     *                     @OA\Items(type="integer")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Visitante actualizado"),
     *     @OA\Response(response=404, description="Visitante no encontrado")
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/visitantes/{id}",
     *     summary="Eliminar un visitante",
     *     tags={"Visitantes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del visitante",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Visitante eliminado"),
     *     @OA\Response(response=404, description="Visitante no encontrado")
     * )
     */
    public function destroy($id)
    {
        $visitante = Visitante::find($id);
        if (!$visitante) {
            return response()->json(['message' => 'Visitante no encontrado'], 404);
        }
        $visitante->delete();
        return response()->json(['message' => 'Visitante eliminado']);
    }

    /**
     * @OA\Post(
     *     path="/api/visitantes/asignar-productos",
     *     summary="Asignar productos de interés a un visitante",
     *     tags={"Visitantes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"visitante_id","productos"},
     *             @OA\Property(property="visitante_id", type="integer", example=1),
     *             @OA\Property(
     *                 property="productos",
     *                 type="array",
     *                 @OA\Items(type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Productos asignados correctamente"),
     *     @OA\Response(response=422, description="Validación de datos fallida")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/visitantes/{visitante_id}/productos",
     *     summary="Obtener productos de interés de un visitante",
     *     tags={"Visitantes"},
     *     @OA\Parameter(
     *         name="visitante_id",
     *         in="path",
     *         description="ID del visitante",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de productos",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Producto"))
     *     ),
     *     @OA\Response(response=404, description="Visitante no encontrado")
     * )
     */
    public function productos($visitante_id)
    {
        $visitante = Visitante::with('productos')->find($visitante_id);
        if (!$visitante) {
            return response()->json(['message' => 'Visitante no encontrado'], 404);
        }
        return response()->json($visitante->productos);
    }

    /**
     * @OA\Get(
     *     path="/api/visitantes/producto/{producto_id}",
     *     summary="Obtener visitantes interesados en un producto",
     *     tags={"Visitantes"},
     *     @OA\Parameter(
     *         name="producto_id",
     *         in="path",
     *         description="ID del producto",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de visitantes",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Visitante"))
     *     ),
     *     @OA\Response(response=404, description="Producto no encontrado")
     * )
     */
    public function visitantesPorProducto($producto_id)
    {
        $producto = Producto::find($producto_id);
        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        return response()->json($producto->visitantes);
    }

    /**
     * @OA\Get(
     *     path="/api/visitantes/exportar",
     *     summary="Exportar la lista de visitantes a CSV",
     *     tags={"Visitantes"},
     *     @OA\Response(
     *         response=200,
     *         description="Archivo CSV generado para descarga",
     *         @OA\MediaType(mediaType="text/csv")
     *     )
     * )
     */
    public function exportar()
    {
        $visitantes = \App\Models\Visitante::with('productos')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="visitantes.csv"',
        ];

        $callback = function () use ($visitantes) {
            $handle = fopen('php://output', 'w');

            // Encabezados CSV
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

            // Datos CSV
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
