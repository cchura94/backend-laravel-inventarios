<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

use function Symfony\Component\Clock\now;

class NotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $query = Nota::with(['user', 'cliente', 'movimientos']);

        // filtros
        if ($request->has("tipo_nota")) {
            $query->where("tipo_nota", $request->tipo_nota);
        }

        if ($request->has("estado_nota")) {
            $query->where("estado_nota", $request->estado_nota);
        }

        if ($request->has("cliente_id")) {
            $query->where("cliente_id", $request->cliente_id);
        }

        if ($request->has("user_id")) {
            $query->where("user_id", $request->user_id);
        }

        if ($request->has(["fecha_inicio", 'fecha_fin'])) {
            $query->where("fecha", [$request->fecha_inicio, $request->fin]);
        }

        // busqueda global

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('codigo_nota', 'iLike', '%' . $request->search . "%")
                    ->orWhere('observaciones', 'ilike', '%' . $request->search . "%");
            });
        }

        // paginación
        $notas = $query->orderByDesc('fecha')->paginate(10);

        return response()->json($notas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validacion
        $request->validate([
            "fecha" => "nullable|date",
            "tipo_nota" => "required|in:venta,compra,devolucion",
            "impuestos" => "nullable",
            "descuentos" => "nullable",
            "total_calculado" => "nullable",
            "estado_nota" => "required|string",
            "observaciones" => "nullable|string",
            "cliente_id" => "required|exists:clientes,id",
            "movimientos" => "required|array|min:1",
            "movimientos.*.producto_id" => "required|exists:productos,id",
            "movimientos.*.almacen_id" => "required|exists:almacens,id",
            "movimientos.*.cantidad" => "required|integer|min:1",
            "movimientos.*.tipo_movimiento" => "required|in:ingreso,salida,devolucion",
            "movimientos.*.precio_unitario_compra" => "required",
            "movimientos.*.precio_unitario_venta" => "required",
            "movimientos.*.observaciones" => "nullable|string",

        ]);

        // transaction
        DB::beginTransaction();

        try {



            $nota = new Nota();
            $nota->fecha = date("Y-m-d H:i:s");
            $nota->tipo_nota = $request->tipo_nota;
            $nota->user_id = $request->user()->id;
            $nota->impuestos = $request->impuestos;
            $nota->descuentos = $request->descuentos;
            $nota->total_calculado = $request->total_calculado;
            $nota->estado_nota = $request->estado_nota;
            $nota->observaciones = $request->observaciones;
            $nota->cliente_id = $request->cliente_id;
            $nota->save();

            foreach ($request->movimientos as $mov) {

                $nota->movimientos()->attach($mov['almacen_id'], [
                    'producto_id' => $mov['producto_id'],
                    'cantidad' => $mov['cantidad'],
                    'tipo_movimiento' => $mov['tipo_movimiento'],
                    'precio_unitario_compra' => $mov['precio_unitario_compra'],
                    'precio_unitario_venta' => $mov['precio_unitario_venta'],
                    'observaciones' => $mov['observaciones'] ?? null,
                ]);
                // actualizar stock
                $pivot = DB::table('almacen_producto')
                    ->where('almacen_id', $mov['almacen_id'])
                    ->where('producto_id', $mov['producto_id'])
                    ->first();

                if (!$pivot) {
                    if ($mov["tipo_movimiento"] === 'salida') {
                        throw new \Exception("No hay stock para salida en este almacen y producto");
                    }
                    DB::table("almacen_producto")->insert([
                        "almacen_id" => $mov["almacen_id"],
                        "producto_id" => $mov["producto_id"],
                        "cantidad_actual" => $mov["cantidad"],
                        "fecha_actualizacion" => now(),
                    ]);
                } else {
                    $nuevaCantidad = $pivot->cantidad_actual;

                    if ($mov['tipo_movimiento'] === 'ingreso' || $mov['tipo_movimiento'] === 'devolucion') {
                        $nuevaCantidad += $mov['cantidad'];
                    } elseif ($mov['tipo_movimiento'] === 'salida') {
                        if ($pivot->cantidad_actual < $mov['cantidad']) {
                            throw new \Exception("Stock Insuficiente para salida");
                        }
                        $nuevaCantidad -= $mov['cantidad'];
                    }

                    DB::table("almacen_producto")
                        ->where('almacen_id', $mov['almacen_id'])
                        ->where('producto_id', $mov['producto_id'])
                        ->update([
                            'cantidad_actual' => $nuevaCantidad,
                            'fecha_actualizacion' => now()
                        ]);
                }

            }
            DB::commit();

            return response()->json(['nota' => $nota->load('movimientos')], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function funReportePDF(Request $request){
        $query = Nota::with(['user', 'cliente']);

        // filtros
        if ($request->has("tipo_nota")) {
            $query->where("tipo_nota", $request->tipo_nota);
        }

        if ($request->has("estado_nota")) {
            $query->where("estado_nota", $request->estado_nota);
        }

        if ($request->has("cliente_id")) {
            $query->where("cliente_id", $request->cliente_id);
        }

        if ($request->has("user_id")) {
            $query->where("user_id", $request->user_id);
        }

        if ($request->has(["fecha_inicio", 'fecha_fin'])) {
            $query->where("fecha", [$request->fecha_inicio, $request->fin]);
        }

        // busqueda global

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('codigo_nota', 'iLike', '%' . $request->search . "%")
                    ->orWhere('observaciones', 'ilike', '%' . $request->search . "%");
            });
        }

        // paginación
        $notas = $query->orderByDesc('fecha')->get();

        $pdf = Pdf::loadView('pdf.notas-compra-venta', ["notas" => $notas]);
        return $pdf->download('notas.pdf');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
